<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashDelivery;
use App\Models\Company;
use App\Models\Gang;
use App\Models\Invoice;
use App\Models\MatrixFund;
use App\Models\MatrixFundMovement;
use Illuminate\Http\JsonResponse;

class FivemDashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $recentDeliveries = CashDelivery::with(['gang', 'company'])
            ->latest()->take(5)->get()
            ->map(fn($d) => [
                'delivery_number' => $d->delivery_number,
                'gang_name'       => $d->gang?->name ?? '—',
                'amount'          => (float) $d->amount,
                'status'          => $d->status,
                'created_at'      => $d->created_at?->format('d/m/Y H:i'),
            ]);

        $recentInvoices = Invoice::with(['gang', 'company'])
            ->latest()->take(5)->get()
            ->map(fn($i) => [
                'invoice_number' => $i->invoice_number,
                'gang_name'      => $i->gang_name_snapshot,
                'concept'        => $i->concept,
                'amount'         => (float) $i->amount,
                'status'         => $i->status,
            ]);

        return response()->json([
            'grossDelivered'          => (float) CashDelivery::sum('amount'),
            'totalMatrixAssigned'     => (float) CashDelivery::sum('matrix_amount'),
            'totalManagementAssigned' => (float) CashDelivery::sum('commission_amount'),
            'totalOperatingAssigned'  => (float) CashDelivery::sum('operating_amount'),
            'totalOperatingBalance'   => (float) Gang::sum('operating_balance'),
            'totalMatrixBalance'      => (float) MatrixFund::sum('balance'),
            'totalMatrixWithdrawn'    => (float) MatrixFundMovement::where('type', 'out')->sum('amount'),
            'totalInvoiced'           => (float) Invoice::sum('amount'),
            'totalCompanies'          => Company::count(),
            'totalGangs'              => Gang::count(),
            'totalCashDeliveries'     => CashDelivery::count(),
            'totalInvoices'           => Invoice::count(),
            'pendingDeliveries'       => CashDelivery::where('status', 'pending')->count(),
            'pendingInvoices'         => Invoice::where('status', 'draft')->count(),
            'cancelledInvoices'       => Invoice::where('status', 'cancelled')->count(),
            'recentDeliveries'        => $recentDeliveries,
            'recentInvoices'          => $recentInvoices,
        ]);
    }
}
