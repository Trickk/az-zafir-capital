<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashDeliveryRequest;
use App\Http\Requests\UpdateCashDeliveryRequest;
use App\Models\CashDelivery;
use App\Models\Gang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashDeliveryController extends Controller
{
    public function index(): View
    {
        $cashDeliveries = CashDelivery::with(['gang', 'company'])
            ->latest()
            ->paginate(12);

        return view('admin.cash-deliveries.index', compact('cashDeliveries'));
    }

    public function create(): View
    {
        $gangs = Gang::with('company')
            ->whereNotNull('company_id')
            ->orderBy('name')
            ->get();

        return view('admin.cash-deliveries.create', compact('gangs'));
    }

    public function store(StoreCashDeliveryRequest $request): RedirectResponse
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

        DB::transaction(function () use ($data, $gang) {
            $delivery = CashDelivery::create([
                'gang_id' => $gang->id,
                'company_id' => $gang->company->id,
                'delivery_number' => $this->generateDeliveryNumber(),
                'amount' => $data['amount'],
                'status' => $data['status'],
                'delivered_by' => $data['delivered_by'] ?? null,
                'received_by' => $data['received_by'] ?? null,
                'delivered_at' => $data['delivered_at'] ?? now(),
                'received_at' => $data['received_at'] ?? now(),
                'created_by' => auth()->id(),
                'notes' => $data['notes'] ?? null,
            ]);

            if (in_array($delivery->status, ['received', 'verified'], true)) {
                $gang->increment('dirty_balance', (float) $delivery->amount);
                $gang->increment('dirty_received_total', (float) $delivery->amount);
            }
        });

        return redirect()
            ->route('admin.cash-deliveries.index')
            ->with('success', 'Entrega de dinero registrada correctamente.');
    }

    public function edit(CashDelivery $cashDelivery): View
    {
        $gangs = Gang::with('company')
            ->whereNotNull('company_id')
            ->orderBy('name')
            ->get();

        return view('admin.cash-deliveries.edit', compact('cashDelivery', 'gangs'));
    }

    public function update(UpdateCashDeliveryRequest $request, CashDelivery $cashDelivery): RedirectResponse
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

        DB::transaction(function () use ($data, $cashDelivery, $gang) {
            $oldAmount = (float) $cashDelivery->amount;
            $oldStatus = $cashDelivery->status;
            $oldGang = Gang::find($cashDelivery->gang_id);

            if ($oldGang && in_array($oldStatus, ['received', 'verified'], true)) {
                if ((float) $oldGang->dirty_balance >= $oldAmount) {
                    $oldGang->decrement('dirty_balance', $oldAmount);
                } else {
                    $oldGang->update(['dirty_balance' => 0]);
                }

                if ((float) $oldGang->dirty_received_total >= $oldAmount) {
                    $oldGang->decrement('dirty_received_total', $oldAmount);
                } else {
                    $oldGang->update(['dirty_received_total' => 0]);
                }
            }

            $cashDelivery->update([
                'gang_id' => $gang->id,
                'company_id' => $gang->company->id,
                'amount' => $data['amount'],
                'status' => $data['status'],
                'delivered_by' => $data['delivered_by'] ?? null,
                'received_by' => $data['received_by'] ?? null,
                'delivered_at' => $data['delivered_at'] ?? null,
                'received_at' => $data['received_at'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            if (in_array($cashDelivery->status, ['received', 'verified'], true)) {
                $gang->increment('dirty_balance', (float) $cashDelivery->amount);
                $gang->increment('dirty_received_total', (float) $cashDelivery->amount);
            }
        });

        return redirect()
            ->route('admin.cash-deliveries.index')
            ->with('success', 'Entrega de dinero actualizada correctamente.');
    }

    public function destroy(CashDelivery $cashDelivery): RedirectResponse
    {
        DB::transaction(function () use ($cashDelivery) {
            $gang = Gang::find($cashDelivery->gang_id);

            if ($gang && in_array($cashDelivery->status, ['received', 'verified'], true)) {
                $amount = (float) $cashDelivery->amount;

                if ((float) $gang->dirty_balance >= $amount) {
                    $gang->decrement('dirty_balance', $amount);
                } else {
                    $gang->update(['dirty_balance' => 0]);
                }

                if ((float) $gang->dirty_received_total >= $amount) {
                    $gang->decrement('dirty_received_total', $amount);
                } else {
                    $gang->update(['dirty_received_total' => 0]);
                }
            }

            $cashDelivery->delete();
        });

        return redirect()
            ->route('admin.cash-deliveries.index')
            ->with('success', 'Entrega de dinero eliminada correctamente.');
    }

    private function generateDeliveryNumber(): string
    {
        $year = now()->format('Y');
        $last = CashDelivery::whereYear('created_at', now()->year)->count() + 1;

        return 'ENT-' . $year . '-' . str_pad((string) $last, 5, '0', STR_PAD_LEFT);
    }
}
