<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Gang;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Spatie\Browsershot\Browsershot;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with(['gang', 'company', 'creator'])
            ->latest()
            ->paginate(12);

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
                ->withErrors(['gang_id' => 'La banda seleccionada no tiene una empresa asociada.'])
                ->withInput();
        }

        $company = $gang->company;
        $amount = round((float) $data['amount'], 2);
        $status = $data['status'];

        if ($status === 'paid' && (float) $gang->operating_balance < $amount) {
            return back()
                ->withErrors(['amount' => 'La banda no tiene saldo operativo suficiente para pagar esta factura.'])
                ->withInput();
        }

        $invoice = null;

        DB::transaction(function () use ($data, $gang, $company, $amount, $status,&$invoice) {
            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'gang_id' => $gang->id,
                'company_id' => $company->id,
                'gang_name_snapshot' => $gang->name,
                'invoice_customer_name' => $data['invoice_customer_name'] ?? null,
                'invoice_state_id' => $data['invoice_state_id'] ?? null,
                'company_name_snapshot' => $company->name,
                'company_legal_name_snapshot' => $company->legal_name,
                'company_tax_id_snapshot' => $company->tax_id,
                'company_responsible_name_snapshot' => $company->responsible_name,
                'company_logo_path_snapshot' => $company->logo_path,
                'company_invoice_image_path_snapshot' => $company->invoice_image_path,
                'concept' => $data['concept'],
                'description' => $data['description'] ?? null,
                'amount' => $amount,
                'status' => $status,
                'issued_at' => $data['issued_at'] ?? now(),
                'paid_at' => $status === 'paid' ? now() : null,
                'cancelled_at' => $status === 'cancelled' ? now() : null,
                'created_by' => auth()->id(),
                'pdf_path' => null,
                'image_path' => null,
            ]);

            if ($invoice->status === 'paid') {
                $this->applyPaidInvoiceImpact($invoice, $gang);
            }


        });

        $this->generateInvoiceImage($invoice);



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
        $gang = Gang::with('company')->findOrFail($data['gang_id']);

        if (! $gang->company) {
            return back()
                ->withErrors(['gang_id' => 'La banda seleccionada no tiene una empresa asociada.'])
                ->withInput();
        }

        $company = $gang->company;
        $newAmount = round((float) $data['amount'], 2);
        $newStatus = $data['status'];

        DB::transaction(function () use ($data, $invoice, $gang, $company, $newAmount, $newStatus) {
            $oldGang = Gang::find($invoice->gang_id);
            $oldStatus = $invoice->status;

            if ($invoice->status === 'paid' && $oldGang) {
                $this->revertPaidInvoiceImpact($invoice, $oldGang);
            }

            if ($invoice->status <> $newStatus && $newStatus === 'paid' && (float) $gang->operating_balance < $newAmount) {
                abort(422, 'La banda no tiene saldo operativo suficiente para pagar esta factura.');
            }

            $invoice->update([
                'gang_id' => $gang->id,
                'company_id' => $company->id,
                'gang_name_snapshot' => $gang->name,
                'company_name_snapshot' => $company->name,
                'company_legal_name_snapshot' => $company->legal_name,
                'company_tax_id_snapshot' => $company->tax_id,
                'company_responsible_name_snapshot' => $company->responsible_name,
                'company_logo_path_snapshot' => $company->logo_path,
                'company_invoice_image_path_snapshot' => $company->invoice_image_path,
                'invoice_customer_name' => $data['invoice_customer_name'] ?? null,
                'invoice_state_id' => $data['invoice_state_id'] ?? null,
                'concept' => $data['concept'],
                'description' => $data['description'] ?? null,
                'amount' => $newAmount,
                'status' => $newStatus,
                'issued_at' => $data['issued_at'] ?? $invoice->issued_at ?? now(),
                'paid_at' => $newStatus === 'paid' ? now() : null,
                'cancelled_at' => $newStatus === 'cancelled' ? now() : null,
            ]);

            if ($oldStatus <> $invoice->status && $invoice->status === 'paid') {
                $this->applyPaidInvoiceImpact($invoice, $gang);
            }
        });

        $this->generateInvoiceImage($invoice->fresh());

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        DB::transaction(function () use ($invoice) {
            $gang = Gang::find($invoice->gang_id);

            if ($invoice->status === 'paid' && $gang) {
                $this->revertPaidInvoiceImpact($invoice, $gang);
            }

            $invoice->delete();
        });

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Factura eliminada correctamente.');
    }

    public function preview(Invoice $invoice): View
    {
        return view('admin.invoices.preview', [
            'invoice' => $invoice,
            'isPdf' => false,
            'isPng' => false,
        ]);
    }

    public function downloadPdf(Invoice $invoice)
    {
       $pdf = Pdf::loadView('admin.invoices.pdf', [
            'invoice' => $invoice,
        ])->setPaper('a4', 'portrait');

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    private function applyPaidInvoiceImpact(Invoice $invoice, Gang $gang): void
    {
        $amount = (float) $invoice->amount;

        if ((float) $gang->operating_balance >= $amount) {
            $gang->decrement('operating_balance', $amount);
        } else {
            abort(422, 'La banda no tiene saldo operativo suficiente para pagar esta factura.');
        }
    }

    private function revertPaidInvoiceImpact(Invoice $invoice, Gang $gang): void
    {
        $gang->increment('operating_balance', (float) $invoice->amount);
    }

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $last = Invoice::withTrashed()
            ->whereYear('created_at', now()->year)
            ->count() + 1;

        return 'FAC-' . $year . '-' . str_pad((string) $last, 5, '0', STR_PAD_LEFT);
    }

    private function generateInvoiceImage(Invoice $invoice): void
    {
        $path = 'invoices/images/' . $invoice->invoice_number . '.png';
        $fullPath = storage_path('app/public/' . $path);

        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $url = route('invoices.image.view', $invoice);

        Browsershot::url($url)
            ->setNodeBinary('/usr/bin/node')
            ->setNpmBinary('/usr/bin/npm')
            ->setChromePath('/usr/bin/google-chrome')
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->timeout(60) // segundos
            ->windowSize(1200, 1700)
            ->deviceScaleFactor(1)
            ->waitUntilNetworkIdle()
            ->save($fullPath);

        $invoice->update([
            'image_path' => $path,
        ]);
    }

    public function imageView(Invoice $invoice): View
    {
        return view('admin.invoices.image', compact('invoice'));
    }

    public function showImage(Invoice $invoice)
    {
        if (!$invoice->image_path) {
            abort(404, 'La imagen de la factura no existe todavía.');
        }

        return redirect(asset('storage/' . $invoice->image_path));
    }
}
