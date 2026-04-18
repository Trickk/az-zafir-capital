<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gang;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FivemInvoiceController extends Controller
{
    public function index(): JsonResponse
    {
        $invoices = Invoice::latest()->get()->map(fn($i) => [
            'id'                    => $i->id,
            'invoice_number'        => $i->invoice_number,
            'gang_id'               => $i->gang_id,
            'gang_name'             => $i->gang_name_snapshot,
            'company_name'          => $i->company_name_snapshot,
            'concept'               => $i->concept,
            'description'           => $i->description,
            'amount'                => (float) $i->amount,
            'status'                => $i->status,
            'invoice_customer_name' => $i->invoice_customer_name,
            'invoice_state_id'      => $i->invoice_state_id,
            'issued_at'             => $i->issued_at?->format('d/m/Y'),
        ]);

        return response()->json(['data' => $invoices]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'gang_id'               => 'required|exists:gangs,id',
            'concept'               => 'required|string|max:180',
            'description'           => 'nullable|string',
            'amount'                => 'required|numeric|min:0.01',
            'status'                => 'nullable|in:draft,issued,paid,cancelled',
            'invoice_customer_name' => 'nullable|string|max:150',
            'invoice_state_id'      => 'nullable|string|max:100',
            'issued_at'             => 'nullable|date',
        ]);

        $gang    = Gang::with('company')->findOrFail($data['gang_id']);
        $company = $gang->company;
        $amount  = round((float) $data['amount'], 2);
        $status  = $data['status'] ?? 'issued';

        if (!$company) {
            return response()->json(['success' => false, 'error' => 'La banda no tiene empresa asociada.'], 422);
        }

        if ($status === 'paid' && (float) $gang->operating_balance < $amount) {
            return response()->json(['success' => false, 'error' => 'Saldo operativo insuficiente.'], 422);
        }

        DB::transaction(function () use ($data, $gang, $company, $amount, $status) {
            $invoice = Invoice::create([
                'invoice_number'                      => $this->generateInvoiceNumber(),
                'gang_id'                             => $gang->id,
                'company_id'                          => $company->id,
                'gang_name_snapshot'                  => $gang->name,
                'invoice_customer_name'               => $data['invoice_customer_name'] ?? null,
                'invoice_state_id'                    => $data['invoice_state_id'] ?? null,
                'company_name_snapshot'               => $company->name,
                'company_legal_name_snapshot'         => $company->legal_name,
                'company_tax_id_snapshot'             => $company->tax_id,
                'company_responsible_name_snapshot'   => $company->responsible_name,
                'company_logo_path_snapshot'          => $company->logo_path,
                'company_invoice_image_path_snapshot' => $company->invoice_image_path,
                'concept'                             => $data['concept'],
                'description'                         => $data['description'] ?? null,
                'amount'                              => $amount,
                'status'                              => $status,
                'issued_at'                           => $data['issued_at'] ?? now(),
                'paid_at'                             => $status === 'paid' ? now() : null,
                'cancelled_at'                        => $status === 'cancelled' ? now() : null,
            ]);

            if ($invoice->status === 'paid') {
                $gang->decrement('operating_balance', $amount);
            }
        });

        return response()->json(['success' => true], 201);
    }

    public function update(Request $request, Invoice $invoice): JsonResponse
    {
        $data = $request->validate([
            'gang_id'               => 'required|exists:gangs,id',
            'concept'               => 'required|string|max:180',
            'description'           => 'nullable|string',
            'amount'                => 'required|numeric|min:0.01',
            'status'                => 'nullable|in:draft,issued,paid,cancelled',
            'invoice_customer_name' => 'nullable|string|max:150',
            'invoice_state_id'      => 'nullable|string|max:100',
            'issued_at'             => 'nullable|date',
        ]);

        $gang      = Gang::with('company')->findOrFail($data['gang_id']);
        $newAmount = round((float) $data['amount'], 2);
        $newStatus = $data['status'] ?? $invoice->status;

        DB::transaction(function () use ($data, $invoice, $gang, $newAmount, $newStatus) {
            if ($invoice->status === 'paid') {
                $oldGang = Gang::find($invoice->gang_id);
                $oldGang?->increment('operating_balance', (float) $invoice->amount);
            }

            if ($newStatus === 'paid' && (float) $gang->operating_balance < $newAmount) {
                abort(422, 'Saldo operativo insuficiente.');
            }

            $invoice->update([
                'gang_id'               => $gang->id,
                'concept'               => $data['concept'],
                'description'           => $data['description'] ?? null,
                'amount'                => $newAmount,
                'status'                => $newStatus,
                'invoice_customer_name' => $data['invoice_customer_name'] ?? null,
                'invoice_state_id'      => $data['invoice_state_id'] ?? null,
                'issued_at'             => $data['issued_at'] ?? $invoice->issued_at ?? now(),
                'paid_at'               => $newStatus === 'paid' ? now() : null,
                'cancelled_at'          => $newStatus === 'cancelled' ? now() : null,
            ]);

            if ($newStatus === 'paid') {
                $gang->decrement('operating_balance', $newAmount);
            }
        });

        return response()->json(['success' => true]);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        DB::transaction(function () use ($invoice) {
            if ($invoice->status === 'paid') {
                $gang = Gang::find($invoice->gang_id);
                $gang?->increment('operating_balance', (float) $invoice->amount);
            }
            $invoice->delete();
        });

        return response()->json(['success' => true]);
    }

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $last = Invoice::withTrashed()->whereYear('created_at', now()->year)->count() + 1;
        return 'FAC-' . $year . '-' . str_pad((string) $last, 5, '0', STR_PAD_LEFT);
    }
}
