<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    public function index()
    {
        $totalTransactions = Transaction::count();
        $pendingPayments = Transaction::where('status', 'pending')->count();
        $processingOrders = Transaction::where('status', 'diproses')->count();
        $completedOrders = Transaction::where('status', 'selesai')->count();
        $totalRevenue = Transaction::where('status', 'selesai')->sum('grand_total');
        $recentTransactions = Transaction::with('user')->latest()->take(10)->get();

        $weeklySales = Transaction::select(
            DB::raw('DAYNAME(created_at) as day'),
            DB::raw('SUM(grand_total) as total')
        )->where('status', 'selesai')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('day')
            ->orderBy('created_at')
            ->get();

        $flashSaleProducts = Product::with('primaryImage')
            ->where('is_flash_sale', true)
            ->where('is_active', true)
            ->whereNotNull('discount_price')
            ->where('discount_price', '<', DB::raw('price'))
            ->latest()
            ->take(10)
            ->get();

        return view('supervisor.dashboard', compact(
            'totalTransactions', 'pendingPayments', 'processingOrders',
            'completedOrders', 'totalRevenue', 'recentTransactions', 'weeklySales',
            'flashSaleProducts'
        ));
    }

    public function analytics()
    {
        $monthlyRevenue = Transaction::select(
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('SUM(grand_total) as total')
        )->where('status', 'selesai')
            ->whereDate('created_at', '>=', now()->subMonths(6))
            ->groupBy('month', DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $topProducts = Product::withCount('transactionItems')
            ->orderBy('transaction_items_count', 'desc')
            ->take(5)->get();

        return view('supervisor.analytics', compact('monthlyRevenue', 'topProducts'));
    }

    public function reports()
    {
        $transactions = Transaction::with('user')->whereDate('created_at', '>=', now()->subMonth())->latest()->get();
        $totalRevenue = $transactions->where('status', 'selesai')->sum('grand_total');
        return view('supervisor.reports', compact('transactions', 'totalRevenue'));
    }

    public function exportReport()
    {
        $transactions = Transaction::with('user', 'items.product')
            ->whereDate('created_at', '>=', now()->subMonth())
            ->get();

        $csv = "Invoice,User,Total,Status,Tanggal\n";
        foreach ($transactions as $t) {
            $csv .= "{$t->invoice},{$t->user->name},{$t->grand_total},{$t->status},{$t->created_at}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-transaksi.csv"',
        ]);
    }
}
