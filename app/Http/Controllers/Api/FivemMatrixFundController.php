<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatrixFund;
use App\Models\MatrixFundMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FivemMatrixFundController extends Controller
{
    public function index(): JsonResponse
    {
        $funds = MatrixFund::with('gang')
            ->whereHas('gang', fn($q) => $q->whereNull('deleted_at'))
            ->get()
            ->map(fn($f) => [
                'id'        => $f->id,
                'gang_name' => $f->gang?->name,
                'balance'   => (float) $f->balance,
                'total_in'  => (float) $f->total_in,
                'total_out' => (float) $f->total_out,
            ]);

        return response()->json(['data' => $funds]);
    }

    public function show(MatrixFund $matrixFund): JsonResponse
    {
        $matrixFund->load(['gang', 'movements' => fn($q) => $q->latest()]);

        return response()->json([
            'id'        => $matrixFund->id,
            'gang_name' => $matrixFund->gang?->name,
            'balance'   => (float) $matrixFund->balance,
            'total_in'  => (float) $matrixFund->total_in,
            'total_out' => (float) $matrixFund->total_out,
            'movements' => $matrixFund->movements->map(fn($m) => [
                'id'         => $m->id,
                'type'       => $m->type,
                'amount'     => (float) $m->amount,
                'concept'    => $m->concept,
                'notes'      => $m->notes,
                'created_at' => $m->created_at?->format('d/m/Y H:i'),
            ]),
        ]);
    }

    public function withdraw(Request $request, MatrixFund $matrixFund): JsonResponse
    {
        $data = $request->validate([
            'amount'  => 'required|numeric|min:0.01',
            'concept' => 'required|string|max:150',
            'notes'   => 'nullable|string',
        ]);

        $amount = round((float) $data['amount'], 2);

        if ((float) $matrixFund->balance < $amount) {
            return response()->json(['success' => false, 'error' => 'Saldo insuficiente en el Matrix Fund.'], 422);
        }

        DB::transaction(function () use ($matrixFund, $data, $amount) {
            $matrixFund->decrement('balance', $amount);
            $matrixFund->increment('total_out', $amount);
            MatrixFundMovement::create([
                'gang_id'        => $matrixFund->gang_id,
                'matrix_fund_id' => $matrixFund->id,
                'type'           => 'out',
                'amount'         => $amount,
                'concept'        => $data['concept'],
                'notes'          => $data['notes'] ?? null,
            ]);
        });

        return response()->json(['success' => true]);
    }
}
