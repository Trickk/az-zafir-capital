<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashDelivery;
use App\Models\Gang;
use App\Models\Company;
use App\Models\Invoice;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalGangs = Gang::count();
        $activeGangs = Gang::where('status', 'active')->count();

        $totalHoldings = Company::count();
        $activeHoldings = Company::where('status', 'active')->count();

        $totalInvoices = Invoice::count();
        $pendingInvoices = Invoice::whereIn('status', ['pending', 'reviewed'])->count();
        $approvedInvoices = Invoice::where('status', 'approved')->count();
        $paidInvoices = Invoice::where('status', 'paid')->count();

        $totalCashRolls = CashDelivery::count();
        $receivedCashRolls = CashDelivery::whereIn('status', ['received', 'verified'])->count();

        $totalDirtyBalance = (float) Gang::sum('dirty_balance');
        $totalDirtyReceived = (float) Gang::sum('dirty_received_total');
        $totalCleaned = (float) Gang::sum('cleaned_total');
        $totalCommission = (float) Gang::sum('commission_paid_total');

        $latestInvoices = Invoice::latest()
            ->take(5)
            ->get();

        $latestCashRolls = CashDelivery::with(['gang', 'company'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalGangs',
            'activeGangs',
            'totalHoldings',
            'activeHoldings',
            'totalInvoices',
            'pendingInvoices',
            'approvedInvoices',
            'paidInvoices',
            'totalCashRolls',
            'receivedCashRolls',
            'totalDirtyBalance',
            'totalDirtyReceived',
            'totalCleaned',
            'totalCommission',
            'latestInvoices',
            'latestCashRolls'
        ));
    }
}
