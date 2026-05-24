<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::where('status', 'selesai')->sum('grand_total');
        $pendingTransactions = Transaction::where('status', 'pending')->count();
        $recentTransactions = Transaction::with('user')->latest()->take(10)->get();
        $recentUsers = User::latest()->take(10)->get();

        $salesChart = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total')
        )->where('status', 'selesai')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $flashSaleProducts = Product::with('primaryImage')
            ->where('is_flash_sale', true)
            ->where('is_active', true)
            ->whereNotNull('discount_price')
            ->where('discount_price', '<', DB::raw('price'))
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProducts', 'totalTransactions', 'totalRevenue',
            'pendingTransactions', 'recentTransactions', 'recentUsers', 'salesChart',
            'flashSaleProducts'
        ));
    }

    public function analytics()
    {
        $totalUsers = User::count();
        $totalTransactions = Transaction::where('status', 'selesai')->count();
        $totalSold = TransactionItem::whereHas('transaction', fn($q) => $q->where('status', 'selesai'))->sum('quantity');
        $averageTransaction = Transaction::where('status', 'selesai')->avg('grand_total') ?? 0;

        $totalUsersLastMonth = User::whereDate('created_at', '<', now()->subMonth())->count();
        $userGrowth = $totalUsersLastMonth > 0
            ? round((($totalUsers - $totalUsersLastMonth) / $totalUsersLastMonth) * 100, 1)
            : 100;

        $userGrowthData = User::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')->orderBy('month')->get();

        $salesByCategory = Product::select('categories.name', DB::raw('COALESCE(SUM(transaction_items.subtotal), 0) as total'))
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->leftJoin('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'selesai')
            ->groupBy('categories.name')
            ->get();

        $topProducts = Product::withCount(['transactionItems as sales_count' => fn($q) => $q->whereHas('transaction', fn($q2) => $q2->where('status', 'selesai'))])
            ->with('images')
            ->orderBy('sales_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.analytics', compact(
            'totalUsers', 'userGrowth', 'userGrowthData',
            'averageTransaction', 'totalSold',
            'salesByCategory', 'topProducts'
        ));
    }

    public function backup()
    {
        $backupDir = storage_path('app/backup');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $files = collect(File::files($backupDir))
            ->filter(fn($f) => $f->getExtension() === 'sql')
            ->map(fn($f) => [
                'filename' => $f->getFilename(),
                'size' => $f->getSize(),
                'created_at' => date('Y-m-d H:i:s', $f->getMTime()),
            ])
            ->sortByDesc('created_at')
            ->values();

        return view('admin.backup', compact('files'));
    }

    public function runBackup()
    {
        try {
            $backupDir = storage_path('app/backup');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
            $path = $backupDir . '/' . $filename;

            $tables = DB::select('SHOW TABLES');
            $dbName = DB::getDatabaseName();
            $key = 'Tables_in_' . $dbName;
            $sql = "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$key;
                $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

                $rows = DB::table($tableName)->get();
                if ($rows->count()) {
                    $columns = implode('`, `', array_keys((array) $rows[0]));
                    $chunks = array_chunk($rows->toArray(), 100);
                    foreach ($chunks as $chunk) {
                        $values = [];
                        foreach ($chunk as $row) {
                            $row = (array) $row;
                            $escaped = array_map(fn($v) => $v === null ? 'NULL' : "'" . addslashes($v) . "'", $row);
                            $values[] = '(' . implode(', ', $escaped) . ')';
                        }
                        $sql .= "INSERT INTO `$tableName` (`$columns`) VALUES\n" . implode(",\n", $values) . ";\n\n";
                    }
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            file_put_contents($path, $sql);

            return back()->with('success', 'Backup database berhasil: ' . $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal backup: ' . $e->getMessage());
        }
    }

    public function downloadBackup($filename)
    {
        $path = storage_path('app/backup/' . basename($filename));
        if (!file_exists($path)) {
            return back()->with('error', 'File backup tidak ditemukan');
        }
        return response()->download($path);
    }

    public function deleteBackup($filename)
    {
        $path = storage_path('app/backup/' . basename($filename));
        if (File::exists($path)) {
            File::delete($path);
            return back()->with('success', 'Backup "' . $filename . '" berhasil dihapus');
        }
        return back()->with('error', 'File backup tidak ditemukan');
    }
}
