<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\TransactionItem;
use App\Exports\FinancialReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminFinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // 1. Summary Cards
        $totalIncome = Transaction::whereIn('status', ['paid', 'diproses', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $totalIncome - $totalExpenses;
        $totalTransactions = Transaction::whereIn('status', ['paid', 'diproses', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Previous Month for indicators
        $prevStart = (clone $startDate)->subMonth();
        $prevEnd = (clone $endDate)->subMonth();
        $prevIncome = Transaction::whereIn('status', ['paid', 'diproses', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('total_amount');
        
        $incomeChange = $prevIncome > 0 ? (($totalIncome - $prevIncome) / $prevIncome) * 100 : 0;

        // 2. Trend Chart (6 Months)
        $months = collect();
        $chartIncome = collect();
        $chartExpenses = collect();

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->format('M Y'));
            
            $chartIncome->push(Transaction::whereIn('status', ['paid', 'diproses', 'dikirim', 'selesai'])
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount'));

            $chartExpenses->push(Expense::whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('amount'));
        }

        // 3. Expense Breakdown Donut
        $categories = ['stok', 'operasional', 'gaji', 'marketing', 'lain-lain'];
        $expenseBreakdown = [];
        foreach ($categories as $cat) {
            $expenseBreakdown[$cat] = Expense::where('category', $cat)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
        }

        return view('admin.financial.index', compact(
            'totalIncome', 'totalExpenses', 'netProfit', 'totalTransactions',
            'incomeChange', 'months', 'chartIncome', 'chartExpenses',
            'expenseBreakdown', 'startDate', 'endDate'
        ));
    }

    public function expenses(Request $request)
    {
        $expenses = Expense::with('admin')
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('admin.financial.expenses', compact('expenses'));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|in:stok,operasional,gaji,marketing,lain-lain',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'receipt' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['admin_id'] = Auth::id();

        if ($request->hasFile('receipt')) {
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create($data);

        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat');
    }

    public function updateExpense(Request $request, Expense $expense)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|in:stok,operasional,gaji,marketing,lain-lain',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'receipt' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($data);

        return redirect()->back()->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroyExpense(Expense $expense)
    {
        $expense->delete();
        return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus');
    }

    public function recap(Request $request)
    {
        $month = $request->month ? Carbon::parse($request->month) : Carbon::now();
        
        $startDate = (clone $month)->startOfMonth();
        $endDate = (clone $month)->endOfMonth();

        // Top 5 Products
        $topProducts = TransactionItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['paid', 'diproses', 'dikirim', 'selesai'])
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Monthly Archive Data
        $archive = Expense::select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month_year"),
                DB::raw("SUM(amount) as total_expenses")
            )
            ->groupBy('month_year')
            ->orderBy('month_year', 'desc')
            ->get()
            ->map(function($item) {
                $m = Carbon::parse($item->month_year . '-01');
                $item->income = Transaction::whereIn('status', ['paid', 'diproses', 'dikirim', 'selesai'])
                    ->whereYear('created_at', $m->year)
                    ->whereMonth('created_at', $m->month)
                    ->sum('total_amount');
                $item->profit = $item->income - $item->total_expenses;
                return $item;
            });

        return view('admin.financial.recap', compact('topProducts', 'archive', 'month'));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        return Excel::download(
            new FinancialReportExport($startDate, $endDate), 
            'Laporan_Keuangan_Trinexa_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.xlsx'
        );
    }
}
