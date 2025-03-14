<?php

namespace App\Http\Controllers;

use App\Models\Bird;
use App\Models\Sale;
use App\Models\Alert;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\EggCollection;
use App\Models\Feed;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $data = [
            'totalBirds' => Bird::count(),
            'todayEggs' => EggCollection::whereDate('collection_date', Carbon::today())->sum('total_eggs'),
            'monthlySales' => Sale::withTotalAmount()
                ->whereMonth('sale_date', Carbon::now()->month)
                ->first()?->items_sum_total_amount ?? 0,
            'activeAlerts' => Alert::where('resolved', false)->count(),
        ];

        return view('dashboards.admin', $data);
    }

    public function manager()
    {
        $branchId = Auth::user()->branch_id;
        
        $data = [
            'totalBirds' => Bird::whereHas('chickPurchase', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })->count(),
            'feedStock' => Feed::where('branch_id', $branchId)->sum('quantity_kg'),
            'monthlyExpenses' => Expense::where('branch_id', $branchId)
                ->whereMonth('expense_date', Carbon::now()->month)
                ->sum('amount'),
            'activeStaff' => User::where('branch_id', $branchId)->count(),
            'lowStockAlerts' => Feed::with(['feedType', 'branch'])
                ->where('branch_id', $branchId)
                ->where('quantity_kg', '<=', 100)
                ->get(),
            'recentExpenses' => Expense::where('branch_id', $branchId)
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboards.manager', $data);
    }

    public function salesManager()
    {
        $branchId = Auth::user()->branch_id;
        
        $data = [
            'todaySales' => Sale::where('branch_id', $branchId)
                ->whereDate('sale_date', Carbon::today())
                ->with('items')
                ->get()
                ->sum(function($sale) {
                    return $sale->items->sum('total_amount');
                }),
            'monthlyRevenue' => Sale::where('branch_id', $branchId)
                ->whereMonth('sale_date', Carbon::now()->month)
                ->with('items')
                ->get()
                ->sum(function($sale) {
                    return $sale->items->sum('total_amount');
                }),
            'activeCustomers' => Buyer::where('branch_id', $branchId)->count(),
            'availableProducts' => Product::where('branch_id', $branchId)
                ->where('quantity', '>', 0)
                ->count(),
            'recentSales' => Sale::with(['buyer', 'items.product'])
                ->where('branch_id', $branchId)
                ->latest()
                ->take(5)
                ->get(),
            'topProducts' => Product::where('branch_id', $branchId)
                ->withCount(['saleItems'])
                ->orderByDesc('sale_items_count')
                ->take(5)
                ->get(),
        ];

        return view('dashboards.salesmanager', $data);
    }

    public function worker()
    {
        $today = Carbon::today();
        
        $data = [
            'todayEggs' => EggCollection::whereDate('collection_date', $today)
                ->where('branch_id', Auth::user()->branch_id)
                ->sum('total_eggs'),
            'birdsUnderCare' => Bird::whereHas('chickPurchase', function($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->count(),
            'pendingTasks' => Alert::where('branch_id', Auth::user()->branch_id)
                ->where('resolved', false)
                ->where('user_id', Auth::id())
                ->count(),
            'healthAlerts' => Alert::where('branch_id', Auth::user()->branch_id)
                ->where('type', 'health')
                ->where('resolved', false)
                ->count(),
            'tasks' => Alert::where('branch_id', Auth::user()->branch_id)
                ->where('user_id', Auth::id())
                ->where('resolved', false)
                ->latest()
                ->get(),
        ];

        return view('dashboards.worker', $data);
    }
}