<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashRollDeliveryRequest;
use App\Http\Requests\UpdateCashRollDeliveryRequest;
use App\Models\CashRollDelivery;
use App\Models\Gang;
use App\Models\Holding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashRollDeliveryController extends Controller
{
    public function index(): View
    {
        $cashRolls = CashRollDelivery::with(['gang', 'holding'])
            ->latest()
            ->paginate(12);

        return view('admin.cash-rolls.index', compact('cashRolls'));
    }

    public function create(): View
    {
        $gangs = Gang::whereHas('holding')
            ->orderBy('name')
            ->get();

        return view('admin.cash-rolls.create', compact('gangs'));
    }

    public function store(StoreCashRollDeliveryRequest $request): RedirectResponse
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
            $delivery = CashRollDelivery::create([
                'gang_id' => $gang->id,
                'holding_id' => $gang->holding->id,
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
                $gang->holding->increment('dirty_balance', (float) $delivery->amount);

                $gang->increment('dirty_balance', (float) $delivery->amount);
                $gang->increment('dirty_received_total', (float) $delivery->amount);
            }
        });

        return redirect()
            ->route('admin.cash-rolls.index')
            ->with('success', 'Rulo de dinero registrado correctamente.');
    }

    public function edit(CashRollDelivery $cashRoll): View
    {
        $gangs = Gang::whereHas('holding')
            ->orderBy('name')
            ->get();

        return view('admin.cash-rolls.edit', compact('cashRoll', 'gangs'));
    }

    public function update(UpdateCashRollDeliveryRequest $request, CashRollDelivery $cashRoll): RedirectResponse
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

        DB::transaction(function () use ($data, $cashRoll, $gang) {
            $originalAmount = (float) $cashRoll->amount;
            $originalStatus = $cashRoll->status;
            $originalGang = Gang::with('holding')->find($cashRoll->gang_id);

            $wasApplied = in_array($originalStatus, ['received', 'verified'], true);

            if ($wasApplied && $originalGang && $originalGang->holding) {
                $originalGang->holding->decrement('dirty_balance', $originalAmount);

                $originalGang->decrement('dirty_balance', $originalAmount);
                $originalGang->decrement('dirty_received_total', $originalAmount);
            }

            $cashRoll->update([
                'gang_id' => $gang->id,
                'holding_id' => $gang->holding->id,
                'amount' => $data['amount'],
                'status' => $data['status'],
                'delivered_by' => $data['delivered_by'] ?? null,
                'received_by' => $data['received_by'] ?? null,
                'delivered_at' => $data['delivered_at'] ?? null,
                'received_at' => $data['received_at'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $shouldApply = in_array($cashRoll->status, ['received', 'verified'], true);

            if ($shouldApply) {
                $gang->holding->increment('dirty_balance', (float) $cashRoll->amount);

                $gang->increment('dirty_balance', (float) $cashRoll->amount);
                $gang->increment('dirty_received_total', (float) $cashRoll->amount);
            }
        });

        return redirect()
            ->route('admin.cash-rolls.index')
            ->with('success', 'Rulo de dinero actualizado correctamente.');
    }

    public function destroy(CashRollDelivery $cashRoll): RedirectResponse
    {
        DB::transaction(function () use ($cashRoll) {
            if (in_array($cashRoll->status, ['received', 'verified'], true)) {
                $gang = Gang::with('holding')->find($cashRoll->gang_id);

                if ($gang && $gang->holding) {
                    $gang->holding->decrement('dirty_balance', (float) $cashRoll->amount);

                    $gang->decrement('dirty_balance', (float) $cashRoll->amount);
                    $gang->decrement('dirty_received_total', (float) $cashRoll->amount);
                }
            }

            $cashRoll->delete();
        });

        return redirect()
            ->route('admin.cash-rolls.index')
            ->with('success', 'Rulo de dinero eliminado correctamente.');
    }

    private function generateDeliveryNumber(): string
    {
        $year = now()->format('Y');
        $last = CashRollDelivery::whereYear('created_at', now()->year)->count() + 1;

        return 'DEL-' . $year . '-' . str_pad((string) $last, 5, '0', STR_PAD_LEFT);
    }
}
