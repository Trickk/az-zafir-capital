<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Gang;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::latest()->paginate(12);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $gangs = Gang::with('company')
            ->whereNotNull('company_id')
            ->orderBy('name')
            ->get();

        return view('admin.invoices.create', compact('gangs'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $gang = Gang::with('company')->findOrFail($data['gang_id']);

        if (! $gang->company) {
            return back()
                ->withErrors([
                    'gang_id' => 'La banda seleccionada no tiene una empresa asociada.',
                ])
                ->withInput();
        }

        $company = $gang->company;

        $gross = (float) $data['gross_amount'];
        $commissionPercent = (float) $gang->commission_percent;
        $settlementPercent = round(100 - $commissionPercent, 2);

        $commissionAmount = round($gross * ($commissionPercent / 100), 2);
        $netAmount = round($gross - $commissionAmount, 2);

        DB::transaction(function () use ($data, $gang, $company, $gross, $settlementPercent, $commissionPercent, $netAmount, $commissionAmount) {
            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'internal_reference' => $this->generateInternalReference(),
                'invoice_customer_name' => $data['invoice_customer_name'] ?? null,
                'invoice_state_id' => $data['invoice_state_id'] ?? null,
                'public_token' => $this->generatePublicToken(),
                'gang_name_snapshot' => $gang->name,
                'company_name_snapshot' => $company->name,
                'company_legal_name_snapshot' => $company->legal_name,
                'company_type_snapshot' => $company->type,
                'company_country_snapshot' => $company->country,
                'company_city_snapshot' => $company->city,
                'company_address_snapshot' => $company->address,
                'company_tax_id_snapshot' => $company->tax_id,
                'company_logo_path_snapshot' => $company->logo_path,
                'company_invoice_image_path_snapshot' => $company->invoice_image_path,

                'concept' => $data['concept'],
                'description' => $data['description'] ?? null,

                'gross_amount' => $gross,
                'settlement_percent' => $settlementPercent,
                'commission_percent' => $commissionPercent,
                'commission_amount' => $commissionAmount,
                'net_amount' => $netAmount,

                'issued_at' => $data['issued_at'],
                'due_at' => $data['due_at'] ?? null,
                'status' => $data['status'],

                'is_generated_image' => false,
                'created_by' => auth()->id(),
                'notes' => $data['notes'] ?? null,
            ]);

            if ($invoice->status === 'approved') {
                $invoice->update([
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
            }

            if ($invoice->status === 'paid') {
                $invoice->update([
                    'paid_at' => now(),
                ]);

                $this->applyPaidInvoiceImpact($invoice, $gang);
            }

            if ($invoice->status === 'cancelled') {
                $invoice->update([
                    'cancelled_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Factura creada correctamente.');
    }

    public function edit(Invoice $invoice): View
    {
        $gangs = Gang::with('company')
            ->whereNotNull('company_id')
            ->orderBy('name')
            ->get();

        return view('admin.invoices.edit', compact('invoice', 'gangs'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validated();

        $gang = Gang::with('company')->findOrFail(id: $data['gang_id']);

        if (! $gang->company) {
            return back()
                ->withErrors([
                    'gang_id' => 'La banda seleccionada no tiene una empresa asociada.',
                ])
                ->withInput();
        }

        $company = $gang->company;

        $gross = (float) $data['gross_amount'];
        $commissionPercent = (float) $gang->commission_percent;
        $settlementPercent = round(100 - $commissionPercent, 2);

        $commissionAmount = round($gross * ($commissionPercent / 100), 2);
        $netAmount = round($gross - $commissionAmount, 2);

        DB::transaction(function () use ($data, $invoice, $gang, $company, $gross, $settlementPercent, $commissionPercent, $netAmount, $commissionAmount) {
            $oldStatus = $invoice->status;
            $oldGrossAmount = (float) $invoice->gross_amount;
            $oldNetAmount = (float) $invoice->net_amount;
            $oldCommissionAmount = (float) $invoice->commission_amount;

            if ($oldStatus === 'paid') {
                $this->revertPaidInvoiceImpact($gang, $oldGrossAmount, $oldNetAmount, $oldCommissionAmount);
            }

            $invoice->update([
                'gang_name_snapshot' => $gang->name,
                'invoice_customer_name' => $data['invoice_customer_name'] ?? null,
                'invoice_state_id' => $data['invoice_state_id'] ?? null,

                'company_name_snapshot' => $company->name,
                'company_legal_name_snapshot' => $company->legal_name,
                'company_type_snapshot' => $company->type,
                'company_country_snapshot' => $company->country,
                'company_city_snapshot' => $company->city,
                'company_address_snapshot' => $company->address,
                'company_tax_id_snapshot' => $company->tax_id,
                'company_logo_path_snapshot' => $company->logo_path,
                'company_invoice_image_path_snapshot' => $company->invoice_image_path,

                'concept' => $data['concept'],
                'description' => $data['description'] ?? null,

                'gross_amount' => $gross,
                'settlement_percent' => $settlementPercent,
                'commission_percent' => $commissionPercent,
                'commission_amount' => $commissionAmount,
                'net_amount' => $netAmount,

                'issued_at' => $data['issued_at'],
                'due_at' => $data['due_at'] ?? null,
                'status' => $data['status'],

                'approved_at' => $data['status'] === 'approved' ? now() : null,
                'approved_by' => $data['status'] === 'approved' ? auth()->id() : null,
                'paid_at' => $data['status'] === 'paid' ? now() : null,
                'cancelled_at' => $data['status'] === 'cancelled' ? now() : null,

                'notes' => $data['notes'] ?? null,
            ]);

            if ($invoice->status === 'paid') {
                $this->applyPaidInvoiceImpact($invoice, $gang);
            }
        });

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $gang = Gang::where('name', $invoice->gang_name_snapshot)->first();

        DB::transaction(function () use ($invoice, $gang) {
            if ($invoice->status === 'paid' && $gang) {
                $this->revertPaidInvoiceImpact(
                    $gang,
                    (float) $invoice->gross_amount,
                    (float) $invoice->net_amount,
                    (float) $invoice->commission_amount
                );
            }

            $invoice->delete();
        });

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Factura eliminada correctamente.');
    }

    private function applyPaidInvoiceImpact(Invoice $invoice, Gang $gang): void
    {
        $gang->increment('cleaned_total', (float) $invoice->net_amount);
        $gang->increment('commission_paid_total', (float) $invoice->commission_amount);

        if ((float) $gang->dirty_balance >= (float) $invoice->gross_amount) {
            $gang->decrement('dirty_balance', (float) $invoice->gross_amount);
        } else {
            $gang->update(['dirty_balance' => 0]);
        }
    }

    private function revertPaidInvoiceImpact(Gang $gang, float $gross, float $net, float $commission): void
    {
        if ((float) $gang->cleaned_total >= $net) {
            $gang->decrement('cleaned_total', $net);
        } else {
            $gang->update(['cleaned_total' => 0]);
        }

        if ((float) $gang->commission_paid_total >= $commission) {
            $gang->decrement('commission_paid_total', $commission);
        } else {
            $gang->update(['commission_paid_total' => 0]);
        }

        $gang->increment('dirty_balance', $gross);
    }

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $last = Invoice::withTrashed()->whereYear('created_at', now()->year)->count() + 1;

        return 'FAC-' . $year . '-' . str_pad((string) $last, 5, '0', STR_PAD_LEFT);
    }

    private function generateInternalReference(): string
    {
        $year = now()->format('Y');
        $last = Invoice::withTrashed()->whereYear('created_at', now()->year)->count() + 1;

        return 'AZ-' . $year . '-' . str_pad((string) $last, 6, '0', STR_PAD_LEFT);
    }

    public function preview(Invoice $invoice): View
{
    return view('admin.invoices.preview', compact('invoice'));
}

public function downloadPdf(Invoice $invoice)
{
    $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'))
        ->setPaper('a4', 'portrait');

    return $pdf->download($invoice->invoice_number . '.pdf');
}

public function generatePdf(Invoice $invoice): RedirectResponse
{
    $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'))
        ->setPaper('a4', 'portrait');

    $fileName = 'invoices/pdf/' . $invoice->invoice_number . '.pdf';

    Storage::disk('public')->put($fileName, $pdf->output());

    $invoice->update([
        'pdf_path' => $fileName,
    ]);

    return redirect()
        ->route('admin.invoices.index')
        ->with('success', 'PDF de factura generado correctamente.');
}

public function publicRender(string $token): View
{
    $invoice = Invoice::where('public_token', $token)->firstOrFail();

    return view('admin.invoices.public-render', [
        'invoice' => $invoice,
        'isPdf' => false,
        'isPng' => true,
    ]);
}

public function generatePng(Invoice $invoice): RedirectResponse
{
    $url = route('invoices.public-render', $invoice->public_token);

    $fileName = 'invoices/png/' . $invoice->invoice_number . '.png';
    $fullPath = storage_path('app/public/' . $fileName);

    if (! is_dir(dirname($fullPath))) {
        mkdir(dirname($fullPath), 0775, true);
    }

    Browsershot::url($url)
        ->windowSize(1400, 1800)
        ->deviceScaleFactor(2)
        ->waitUntilNetworkIdle()
        ->showBackground()
        ->save($fullPath);

    $publicUrl = asset('storage/' . $fileName);

    $invoice->update([
        'png_path' => $fileName,
        'public_image_url' => $publicUrl,
        'is_generated_image' => true,
        'public_image_path' => $fileName,
    ]);

    return redirect()
        ->route('admin.invoices.index')
        ->with('success', 'PNG de factura generado correctamente.');
}

private function generatePublicToken(): string
{
    do {
        $token = \Illuminate\Support\Str::random(40);
    } while (\App\Models\Invoice::where('public_token', $token)->exists());

    return $token;
}

}
