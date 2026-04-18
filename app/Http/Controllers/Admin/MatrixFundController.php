<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gang;
use App\Models\MatrixFund;
use App\Models\MatrixFundMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Requests\StoreMatrixFundWithdrawalRequest;

class MatrixFundController extends Controller
{
    public function index(): View
    {
        $funds = MatrixFund::with('gang')
            ->whereHas('gang', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->latest()
            ->paginate(12);

        return view('admin.matrix-funds.index', compact('funds'));
    }

    public function show(MatrixFund $matrixFund): View
    {
        $matrixFund->load([
            'gang',
            'movements' => fn ($query) => $query->latest(),
        ]);

        return view('admin.matrix-funds.show', compact('matrixFund'));
    }

    public function withdrawForm(MatrixFund $matrixFund): View
    {
        $matrixFund->load('gang');

        return view('admin.matrix-funds.withdraw', compact('matrixFund'));
    }

    public function withdraw(StoreMatrixFundWithdrawalRequest $request, MatrixFund $matrixFund): RedirectResponse
{
    $data = $request->validated();

    $amount = round((float) $data['amount'], 2);

    if ((float) $matrixFund->balance < $amount) {
        return back()
            ->withErrors(['amount' => 'No hay saldo suficiente en el Matrix Fund.'])
            ->withInput();
    }

    DB::transaction(function () use ($matrixFund, $data, $amount) {
        $matrixFund->decrement('balance', $amount);
        $matrixFund->increment('total_out', $amount);

        MatrixFundMovement::create([
            'gang_id' => $matrixFund->gang_id,
            'matrix_fund_id' => $matrixFund->id,
            'type' => 'out',
            'amount' => $amount,
            'concept' => $data['concept'],
            'notes' => $data['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);
    });

    return redirect()
        ->route('admin.matrix-funds.show', $matrixFund)
        ->with('success', 'Retirada registrada correctamente.');
}

public function destroy(MatrixFund $matrixFund): RedirectResponse
    {

        MatrixFundMovement::where('matrix_fund_id' == $matrixFund->id)->delete();

        $matrixFund->delete();

        return redirect()
            ->route('admin.matrix-funds.index')
            ->with('success', 'Eliminado correctamente.');
    }
}
