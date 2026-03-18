<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Company;
use App\Models\Gang;
use App\Models\Invoice;
use App\Models\Settlement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with(['gang', 'holding', 'company', 'settlement'])
            ->latest()
            ->paginate(12);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $gangs = Gang::whereHas('holding')
            ->orderBy('name')
            ->get();

        $companies = Company::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.invoices.create', compact('gangs', 'companies'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $gang = Gang::with('holding')->findOrFail($data['gang_id']);

        if (! $gang->holding) {
            return back()
                ->withErrors([
                    'gang_id' => 'La banda seleccionada no tiene un holding asignado.',
                ])
                ->withInput();
        }

        DB::transaction(function () use ($data, $gang) {
            $invoice = Invoice::create([
                'gang_id' => $gang->id,
                'holding_id' => $gang->holding->id,
                'company_id' => $data['company_id'],
                'invoice_number' => $this->generateInvoiceNumber(),
                'internal_reference' => $this->generateInternalReference(),
                'concept' => $data['concept'],
                'description' => $data['description'] ?? null,
                'gross_amount' => $data['gross_amount'],
                'issued_at' => $data['issued_at'],
                'due_at' => $data['due_at'] ?? null,
                'status' => $data['status'],
                'is_generated_image' => false,
                'created_by' => auth()->id(),
                'notes' => $data['notes'] ?? null,
            ]);

            $this->syncSettlement($invoice);

            if ($invoice->status === 'paid') {
                $this->applyPaidInvoiceImpact($invoice);
            }
        });

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Factura creada correctamente.');
    }

    public function edit(Invoice $invoice): View
    {
        $gangs = Gang::whereHas('holding')
            ->orderBy('name')
            ->get();

        $companies = Company::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.invoices.edit', compact('invoice', 'gangs', 'companies'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validated();

        $gang = Gang::with('holding')->findOrFail($data['gang_id']);

        if (! $gang->holding) {
            return back()
                ->withErrors([
                    'gang_id' => 'La banda seleccionada no tiene un holding asignado.',
                ])
                ->withInput();
        }

        DB::transaction(function () use ($data, $invoice, $gang) {
            $previousStatus = $invoice->status;

            $invoice->update([
                'gang_id' => $gang->id,
                'holding_id' => $gang->holding->id,
                'company_id' => $data['company_id'],
                'concept' => $data['concept'],
                'description' => $data['description'] ?? null,
                'gross_amount' => $data['gross_amount'],
                'issued_at' => $data['issued_at'],
                'due_at' => $data['due_at'] ?? null,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->syncSettlement($invoice);

            if ($previousStatus !== 'paid' && $invoice->status === 'paid') {
                $this->applyPaidInvoiceImpact($invoice);
            }
        });

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        DB::transaction(function () use ($invoice) {
            if ($invoice->settlement) {
                $invoice->settlement->delete();
            }

            $invoice->delete();
        });

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Factura eliminada correctamente.');
    }

    private function syncSettlement(Invoice $invoice): void
    {
        $gross = (float) $invoice->gross_amount;
        $commissionPercent = 20.00;
        $commissionAmount = round($gross * ($commissionPercent / 100), 2);
        $netAmount = round($gross - $commissionAmount, 2);

        $status = match ($invoice->status) {
            'paid' => 'released',
            'approved' => 'processed',
            'cancelled', 'rejected' => 'cancelled',
            default => 'pending',
        };

        Settlement::updateOrCreate(
            ['invoice_id' => $invoice->id],
            [
                'gang_id' => $invoice->gang_id,
                'holding_id' => $invoice->holding_id,
                'settlement_number' => $invoice->settlement?->settlement_number ?? $this->generateSettlementNumber(),
                'gross_amount' => $gross,
                'commission_percent' => $commissionPercent,
                'commission_amount' => $commissionAmount,
                'net_amount' => $netAmount,
                'status' => $status,
                'processed_at' => $status === 'processed' ? now() : null,
                'released_at' => $status === 'released' ? now() : null,
                'processed_by' => $status === 'processed' ? auth()->id() : null,
                'released_by' => $status === 'released' ? auth()->id() : null,
                'notes' => 'Liquidación generada automáticamente desde la factura ' . $invoice->invoice_number,
            ]
        );
    }

    private function applyPaidInvoiceImpact(Invoice $invoice): void
    {
        $gross = (float) $invoice->gross_amount;
        $commissionAmount = round($gross * 0.20, 2);
        $netAmount = round($gross - $commissionAmount, 2);

        $gang = $invoice->gang;
        $holding = $invoice->holding;

        if ($gang) {
            $gang->increment('cleaned_total', $netAmount);
            $gang->increment('commission_paid_total', $commissionAmount);

            if ((float) $gang->dirty_balance >= $gross) {
                $gang->decrement('dirty_balance', $gross);
            } else {
                $gang->update(['dirty_balance' => 0]);
            }
        }

        if ($holding) {
            $holding->increment('cleaned_total', $netAmount);
            $holding->increment('commission_paid_total', $commissionAmount);

            if ((float) $holding->dirty_balance >= $gross) {
                $holding->decrement('dirty_balance', $gross);
            } else {
                $holding->update(['dirty_balance' => 0]);
            }
        }
    }

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $last = Invoice::whereYear('created_at', now()->year)->count() + 1;

        return 'INV-' . $year . '-' . str_pad((string) $last, 5, '0', STR_PAD_LEFT);
    }

    private function generateInternalReference(): string
    {
        $year = now()->format('Y');
        $last = Invoice::whereYear('created_at', now()->year)->count() + 1;

        return 'AZ-' . $year . '-' . str_pad((string) $last, 6, '0', STR_PAD_LEFT);
    }

    private function generateSettlementNumber(): string
    {
        $year = now()->format('Y');
        $last = Settlement::whereYear('created_at', now()->year)->count() + 1;

        return 'SET-' . $year . '-' . str_pad((string) $last, 5, '0', STR_PAD_LEFT);
    }
}
