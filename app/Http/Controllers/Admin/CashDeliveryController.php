<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashDeliveryRequest;
use App\Http\Requests\UpdateCashDeliveryRequest;
use App\Models\CashDelivery;
use App\Models\Gang;
use App\Models\MatrixFund;
use App\Models\MatrixFundMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashDeliveryController extends Controller
{
    public function index(): View
    {
        $cashDeliveries = CashDelivery::with(['gang', 'company', 'creator'])
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
        $gang = Gang::with(['company', 'matrixFund'])->findOrFail($data['gang_id']);

        if (! $gang->company) {
            return back()
                ->withErrors(['gang_id' => 'La banda seleccionada no tiene una empresa asociada.'])
                ->withInput();
        }

        DB::transaction(function () use ($data, $gang) {
            $amount = round((float) $data['amount'], 2);
            $matrixPercent = round((float) ($gang->matrix_percent ?? 10), 2);
            $commissionPercent = round((float) ($gang->commission_percent ?? 10), 2);
            $operatingPercent = round(100 - $matrixPercent - $commissionPercent, 2);

            if ($operatingPercent < 0) {
                abort(422, 'La suma de porcentajes de Matrix y gestor no puede ser superior a 100.');
            }

            $matrixAmount = round($amount * ($matrixPercent / 100), 2);
            $commissionAmount = round($amount * ($commissionPercent / 100), 2);
            $operatingAmount = round($amount - $matrixAmount - $commissionAmount, 2);

            $delivery = CashDelivery::create([
                'gang_id' => $gang->id,
                'company_id' => $gang->company->id,
                'delivery_number' => $this->generateDeliveryNumber(),
                'amount' => $amount,
                'matrix_percent' => $matrixPercent,
                'commission_percent' => $commissionPercent,
                'operating_percent' => $operatingPercent,
                'matrix_amount' => $matrixAmount,
                'commission_amount' => $commissionAmount,
                'operating_amount' => $operatingAmount,
                'status' => $data['status'],
                'created_by' => auth()->id(),
                'notes' => $data['notes'] ?? null,
            ]);

            if (in_array($delivery->status, ['received', 'verified'], true)) {
                $gang->increment('operating_balance', $operatingAmount);

                $matrixFund = $gang->matrixFund ?: MatrixFund::create([
                    'gang_id' => $gang->id,
                    'balance' => 0,
                    'total_in' => 0,
                    'total_out' => 0,
                ]);

                $matrixFund->increment('balance', $matrixAmount);
                $matrixFund->increment('total_in', $matrixAmount);

                MatrixFundMovement::create([
                    'gang_id' => $gang->id,
                    'matrix_fund_id' => $matrixFund->id,
                    'type' => 'in',
                    'amount' => $matrixAmount,
                    'concept' => 'Asignación automática por entrega ' . $delivery->delivery_number,
                    'notes' => $data['notes'] ?? null,
                    'created_by' => auth()->id(),
                ]);
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
        $newGang = Gang::with(['company', 'matrixFund'])->findOrFail($data['gang_id']);

        if (! $newGang->company) {
            return back()
                ->withErrors(['gang_id' => 'La banda seleccionada no tiene una empresa asociada.'])
                ->withInput();
        }

        DB::transaction(function () use ($data, $cashDelivery, $newGang) {
            $oldGang = Gang::with('matrixFund')->find($cashDelivery->gang_id);

            if ($oldGang && in_array($cashDelivery->status, ['received', 'verified'], true)) {
                $this->revertDeliveryImpact($cashDelivery, $oldGang);
            }

            $amount = round((float) $data['amount'], 2);
            $matrixPercent = round((float) ($newGang->matrix_percent ?? 10), 2);
            $commissionPercent = round((float) ($newGang->commission_percent ?? 10), 2);
            $operatingPercent = round(100 - $matrixPercent - $commissionPercent, 2);

            if ($operatingPercent < 0) {
                abort(422, 'La suma de porcentajes de Matrix y gestor no puede ser superior a 100.');
            }

            $matrixAmount = round($amount * ($matrixPercent / 100), 2);
            $commissionAmount = round($amount * ($commissionPercent / 100), 2);
            $operatingAmount = round($amount - $matrixAmount - $commissionAmount, 2);

            $cashDelivery->update([
                'gang_id' => $newGang->id,
                'company_id' => $newGang->company->id,
                'amount' => $amount,
                'matrix_percent' => $matrixPercent,
                'commission_percent' => $commissionPercent,
                'operating_percent' => $operatingPercent,
                'matrix_amount' => $matrixAmount,
                'commission_amount' => $commissionAmount,
                'operating_amount' => $operatingAmount,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);

            if (in_array($cashDelivery->status, ['received', 'verified'], true)) {
                $newGang->increment('operating_balance', $operatingAmount);

                $matrixFund = $newGang->matrixFund ?: MatrixFund::create([
                    'gang_id' => $newGang->id,
                    'balance' => 0,
                    'total_in' => 0,
                    'total_out' => 0,
                ]);

                $matrixFund->increment('balance', $matrixAmount);
                $matrixFund->increment('total_in', $matrixAmount);

                MatrixFundMovement::create([
                    'gang_id' => $newGang->id,
                    'matrix_fund_id' => $matrixFund->id,
                    'type' => 'in',
                    'amount' => $matrixAmount,
                    'concept' => 'Reasignación automática por edición de entrega ' . $cashDelivery->delivery_number,
                    'notes' => $data['notes'] ?? null,
                    'created_by' => auth()->id(),
                ]);
            }
        });

        return redirect()
            ->route('admin.cash-deliveries.index')
            ->with('success', 'Entrega de dinero actualizada correctamente.');
    }

    public function destroy(CashDelivery $cashDelivery): RedirectResponse
    {
        DB::transaction(function () use ($cashDelivery) {
            $gang = Gang::with('matrixFund')->find($cashDelivery->gang_id);

            if ($gang && in_array($cashDelivery->status, ['received', 'verified'], true)) {
                $this->revertDeliveryImpact($cashDelivery, $gang);
            }

            $cashDelivery->delete();
        });

        return redirect()
            ->route('admin.cash-deliveries.index')
            ->with('success', 'Entrega de dinero eliminada correctamente.');
    }

    private function revertDeliveryImpact(CashDelivery $cashDelivery, Gang $gang): void
    {
        $operatingAmount = (float) $cashDelivery->operating_amount;
        $matrixAmount = (float) $cashDelivery->matrix_amount;

        if ((float) $gang->operating_balance >= $operatingAmount) {
            $gang->decrement('operating_balance', $operatingAmount);
        } else {
            $gang->update(['operating_balance' => 0]);
        }

        $matrixFund = $gang->matrixFund;
        if ($matrixFund) {
            if ((float) $matrixFund->balance >= $matrixAmount) {
                $matrixFund->decrement('balance', $matrixAmount);
            } else {
                $matrixFund->update(['balance' => 0]);
            }

            if ((float) $matrixFund->total_in >= $matrixAmount) {
                $matrixFund->decrement('total_in', $matrixAmount);
            } else {
                $matrixFund->update(['total_in' => 0]);
            }

            MatrixFundMovement::create([
                'gang_id' => $gang->id,
                'matrix_fund_id' => $matrixFund->id,
                'type' => 'adjustment',
                'amount' => $matrixAmount,
                'concept' => 'Reversión de entrega ' . $cashDelivery->delivery_number,
                'notes' => 'Ajuste automático por edición o eliminación de entrega.',
                'created_by' => auth()->id(),
            ]);
        }
    }

    private function generateDeliveryNumber(): string
    {
        $year = now()->format('Y');
        $last = CashDelivery::whereYear('created_at', now()->year)->count() + 1;

        return 'ENT-' . $year . '-' . str_pad((string) $last, 5, '0', STR_PAD_LEFT);
    }
}
