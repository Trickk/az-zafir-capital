<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashRollDelivery;
use App\Models\Gang;
use App\Models\Holding;
use App\Models\Invoice;
use App\Models\Settlement;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalGangs = Gang::count();
        $activeGangs = Gang::where('status', 'active')->count();

        $totalHoldings = Holding::count();
        $activeHoldings = Holding::where('status', 'active')->count();

        $totalInvoices = Invoice::count();
        $pendingInvoices = Invoice::whereIn('status', ['pending', 'reviewed'])->count();
        $approvedInvoices = Invoice::where('status', 'approved')->count();
        $paidInvoices = Invoice::where('status', 'paid')->count();

        $totalCashRolls = CashRollDelivery::count();
        $receivedCashRolls = CashRollDelivery::whereIn('status', ['received', 'verified'])->count();

        $totalDirtyBalance = (float) Gang::sum('dirty_balance');
        $totalDirtyReceived = (float) Gang::sum('dirty_received_total');
        $totalCleaned = (float) Gang::sum('cleaned_total');
        $totalCommission = (float) Gang::sum('commission_paid_total');

        $pendingSettlements = Settlement::where('status', 'pending')->count();
        $processedSettlements = Settlement::where('status', 'processed')->count();
        $releasedSettlements = Settlement::where('status', 'released')->count();

        $latestInvoices = Invoice::with(['gang', 'holding', 'company'])
            ->latest()
            ->take(5)
            ->get();

        $latestCashRolls = CashRollDelivery::with(['gang', 'holding'])
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
            'pendingSettlements',
            'processedSettlements',
            'releasedSettlements',
            'latestInvoices',
            'latestCashRolls'
        ));
    }
}
