<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashDelivery;
use App\Models\Gang;
use App\Models\MatrixFund;
use App\Models\MatrixFundMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FivemCashDeliveryController extends Controller
{
    public function index(): JsonResponse
    {
        $deliveries = CashDelivery::with(['gang', 'company'])
            ->latest()->get()
            ->map(fn($d) => [
                'id'                => $d->id,
                'delivery_number'   => $d->delivery_number,
                'gang_id'           => $d->gang_id,
                'gang_name'         => $d->gang?->name ?? '—',
                'company_name'      => $d->company?->name ?? '—',
                'amount'            => (float) $d->amount,
                'matrix_amount'     => (float) $d->matrix_amount,
                'commission_amount' => (float) $d->commission_amount,
                'operating_amount'  => (float) $d->operating_amount,
                'status'            => $d->status,
                'notes'             => $d->notes,
                'created_at'        => $d->created_at?->format('d/m/Y H:i'),
            ]);

        return response()->json(['data' => $deliveries]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'gang_id' => 'required|exists:gangs,id',
            'amount'  => 'required|numeric|min:0.01',
            'status'  => 'nullable|in:pending,received,verified,cancelled',
            'notes'   => 'nullable|string',
        ]);

        $gang = Gang::with(['company', 'matrixFund'])->findOrFail($data['gang_id']);

        if (!$gang->company) {
            return response()->json(['success' => false, 'error' => 'La banda no tiene empresa asociada.'], 422);
        }

        DB::transaction(function () use ($data, $gang) {
            $amount    = round((float) $data['amount'], 2);
            $matrixPct = round((float) ($gang->matrix_percent ?? 10), 2);
            $commPct   = round((float) ($gang->commission_percent ?? 10), 2);
            $opPct     = round(100 - $matrixPct - $commPct, 2);
            $matrixAmt = round($amount * ($matrixPct / 100), 2);
            $commAmt   = round($amount * ($commPct / 100), 2);
            $opAmt     = round($amount - $matrixAmt - $commAmt, 2);

            $delivery = CashDelivery::create([
                'gang_id'            => $gang->id,
                'company_id'         => $gang->company->id,
                'delivery_number'    => $this->generateDeliveryNumber(),
                'amount'             => $amount,
                'matrix_percent'     => $matrixPct,
                'commission_percent' => $commPct,
                'operating_percent'  => $opPct,
                'matrix_amount'      => $matrixAmt,
                'commission_amount'  => $commAmt,
                'operating_amount'   => $opAmt,
                'status'             => $data['status'] ?? 'received',
                'notes'              => $data['notes'] ?? null,
            ]);

            if (in_array($delivery->status, ['received', 'verified'], true)) {
                $gang->increment('operating_balance', $opAmt);
                $matrixFund = $gang->matrixFund ?: MatrixFund::create([
                    'gang_id'  => $gang->id,
                    'balance'  => 0,
                    'total_in' => 0,
                    'total_out'=> 0,
                ]);
                $matrixFund->increment('balance', $matrixAmt);
                $matrixFund->increment('total_in', $matrixAmt);
                MatrixFundMovement::create([
                    'gang_id'        => $gang->id,
                    'matrix_fund_id' => $matrixFund->id,
                    'type'           => 'in',
                    'amount'         => $matrixAmt,
                    'concept'        => 'Asignación automática por entrega ' . $delivery->delivery_number,
                    'notes'          => $data['notes'] ?? null,
                ]);
            }
        });

        return response()->json(['success' => true], 201);
    }

    public function update(Request $request, CashDelivery $cashDelivery): JsonResponse
    {
        $data = $request->validate([
            'gang_id' => 'required|exists:gangs,id',
            'amount'  => 'required|numeric|min:0.01',
            'status'  => 'nullable|in:pending,received,verified,cancelled',
            'notes'   => 'nullable|string',
        ]);

        $newGang = Gang::with(['company', 'matrixFund'])->findOrFail($data['gang_id']);

        if (!$newGang->company) {
            return response()->json(['success' => false, 'error' => 'La banda no tiene empresa asociada.'], 422);
        }

        DB::transaction(function () use ($data, $cashDelivery, $newGang) {
            $oldGang = Gang::with('matrixFund')->find($cashDelivery->gang_id);
            if ($oldGang && in_array($cashDelivery->status, ['received', 'verified'], true)) {
                $this->revertDeliveryImpact($cashDelivery, $oldGang);
            }

            $amount    = round((float) $data['amount'], 2);
            $matrixPct = round((float) ($newGang->matrix_percent ?? 10), 2);
            $commPct   = round((float) ($newGang->commission_percent ?? 10), 2);
            $opPct     = round(100 - $matrixPct - $commPct, 2);
            $matrixAmt = round($amount * ($matrixPct / 100), 2);
            $commAmt   = round($amount * ($commPct / 100), 2);
            $opAmt     = round($amount - $matrixAmt - $commAmt, 2);

            $cashDelivery->update([
                'gang_id'            => $newGang->id,
                'company_id'         => $newGang->company->id,
                'amount'             => $amount,
                'matrix_percent'     => $matrixPct,
                'commission_percent' => $commPct,
                'operating_percent'  => $opPct,
                'matrix_amount'      => $matrixAmt,
                'commission_amount'  => $commAmt,
                'operating_amount'   => $opAmt,
                'status'             => $data['status'] ?? $cashDelivery->status,
                'notes'              => $data['notes'] ?? null,
            ]);

            if (in_array($cashDelivery->status, ['received', 'verified'], true)) {
                $newGang->increment('operating_balance', $opAmt);
                $matrixFund = $newGang->matrixFund ?: MatrixFund::create([
                    'gang_id'  => $newGang->id,
                    'balance'  => 0,
                    'total_in' => 0,
                    'total_out'=> 0,
                ]);
                $matrixFund->increment('balance', $matrixAmt);
                $matrixFund->increment('total_in', $matrixAmt);
                MatrixFundMovement::create([
                    'gang_id'        => $newGang->id,
                    'matrix_fund_id' => $matrixFund->id,
                    'type'           => 'in',
                    'amount'         => $matrixAmt,
                    'concept'        => 'Reasignación por edición de entrega ' . $cashDelivery->delivery_number,
                    'notes'          => $data['notes'] ?? null,
                ]);
            }
        });

        return response()->json(['success' => true]);
    }

    public function destroy(CashDelivery $cashDelivery): JsonResponse
    {
        DB::transaction(function () use ($cashDelivery) {
            $gang = Gang::with('matrixFund')->find($cashDelivery->gang_id);
            if ($gang && in_array($cashDelivery->status, ['received', 'verified'], true)) {
                $this->revertDeliveryImpact($cashDelivery, $gang);
            }
            $cashDelivery->delete();
        });

        return response()->json(['success' => true]);
    }

    private function revertDeliveryImpact(CashDelivery $cashDelivery, Gang $gang): void
    {
        $opAmt     = (float) $cashDelivery->operating_amount;
        $matrixAmt = (float) $cashDelivery->matrix_amount;

        $gang->operating_balance >= $opAmt
            ? $gang->decrement('operating_balance', $opAmt)
            : $gang->update(['operating_balance' => 0]);

        $mf = $gang->matrixFund;
        if ($mf) {
            $mf->balance  >= $matrixAmt ? $mf->decrement('balance', $matrixAmt)  : $mf->update(['balance' => 0]);
            $mf->total_in >= $matrixAmt ? $mf->decrement('total_in', $matrixAmt) : $mf->update(['total_in' => 0]);
            MatrixFundMovement::create([
                'gang_id'        => $gang->id,
                'matrix_fund_id' => $mf->id,
                'type'           => 'adjustment',
                'amount'         => $matrixAmt,
                'concept'        => 'Reversión de entrega ' . $cashDelivery->delivery_number,
                'notes'          => 'Ajuste automático por edición o eliminación.',
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
