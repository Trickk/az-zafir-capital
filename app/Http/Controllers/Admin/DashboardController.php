<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashDelivery;
use App\Models\Company;
use App\Models\Gang;
use App\Models\Invoice;
use App\Models\MatrixFund;
use App\Models\MatrixFundMovement;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalCompanies = Company::count();
        $totalGangs = Gang::count();
        $totalCashDeliveries = CashDelivery::count();
        $totalInvoices = Invoice::count();

        $grossDelivered = (float) CashDelivery::sum('amount');
        $totalMatrixAssigned = (float) CashDelivery::sum('matrix_amount');
        $totalManagementAssigned = (float) CashDelivery::sum('commission_amount');
        $totalOperatingAssigned = (float) CashDelivery::sum('operating_amount');

        $totalOperatingBalance = (float) Gang::sum('operating_balance');
        $totalMatrixBalance = (float) MatrixFund::sum('balance');
        $totalMatrixWithdrawn = (float) MatrixFundMovement::where('type', 'out')->sum('amount');
        $totalInvoiced = (float) Invoice::sum('amount');

        $pendingDeliveries = CashDelivery::where('status', 'pending')->count();
        $pendingInvoices = Invoice::where('status', 'draft')->count();
        $cancelledInvoices = Invoice::where('status', 'cancelled')->count();

        $recentDeliveries = CashDelivery::with(['gang', 'company'])
            ->latest()
            ->take(5)
            ->get();

        $recentInvoices = Invoice::with(['gang', 'company'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCompanies',
            'totalGangs',
            'totalCashDeliveries',
            'totalInvoices',
            'grossDelivered',
            'totalMatrixAssigned',
            'totalManagementAssigned',
            'totalOperatingAssigned',
            'totalOperatingBalance',
            'totalMatrixBalance',
            'totalMatrixWithdrawn',
            'totalInvoiced',
            'pendingDeliveries',
            'pendingInvoices',
            'cancelledInvoices',
            'recentDeliveries',
            'recentInvoices'
        ));
    }
}
