<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentMonth = now()->startOfMonth();
        $currentYear = now()->startOfYear();

        // Basic stats
        $thisMonthAmount = $user->expenses()->thisMonth()->sum('amount');
        $lastMonthAmount = $user->expenses()->lastMonth()->sum('amount');
        $thisWeekAmount = $user->expenses()->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');
        $lastWeekAmount = $user->expenses()->whereBetween('date', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->sum('amount');
        $todayAmount = $user->expenses()->whereDate('date', today())->sum('amount');
        $yesterdayAmount = $user->expenses()->whereDate('date', now()->subDay())->sum('amount');
        $todayTransactionCount = $user->expenses()->whereDate('date', today())->count();
        $thisWeekTransactionCount = $user->expenses()->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $stats = [
            'thisMonth' => $thisMonthAmount,
            'thisWeek' => $thisWeekAmount,
            'today' => $todayAmount,
            'lastMonth' => $lastMonthAmount,
            'monthlyTrend' => $lastMonthAmount > 0 ? (($thisMonthAmount - $lastMonthAmount) / $lastMonthAmount) * 100 : 0,
            'weeklyTrend' => $lastWeekAmount > 0 ? (($thisWeekAmount - $lastWeekAmount) / $lastWeekAmount) * 100 : 0,
            'dailyTrend' => $yesterdayAmount > 0 ? (($todayAmount - $yesterdayAmount) / $yesterdayAmount) * 100 : 0,
            'totalExpenses' => $user->expenses()->count(),
            'avgDaily' => $user->expenses()->thisMonth()->avg('amount') ?? 0,
            'todayTransactionCount' => $todayTransactionCount,
            'thisWeekTransactionCount' => $thisWeekTransactionCount,
        ];

        // Budget information
        $currentBudget = \App\Models\UserBudget::getOrCreateCurrentBudget(Auth::id(), 400000);
        $monthlyBudget = $currentBudget->monthly_budget;
        $budgetUsed = ($monthlyBudget > 0) ? ($thisMonthAmount / $monthlyBudget) * 100 : 0;
        $budgetRemaining = max(0, $monthlyBudget - $thisMonthAmount);

        $budget = [
            'monthly' => $monthlyBudget,
            'used' => $thisMonthAmount,
            'remaining' => $budgetRemaining,
            'percentage' => $budgetUsed,
            'budget_id' => $currentBudget->id,
        ];

        // Category breakdown for pie chart
        $categoryData = $user->expenses()
            ->select('categories.name', 'categories.color', DB::raw('SUM(expenses.amount) as total'), DB::raw('COUNT(*) as count'))
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->thisMonth()
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderBy('total', 'desc')
            ->get();

        // Daily expenses for line chart (last 30 days)
        $dailyExpenses = $user->expenses()
            ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(amount) as total'))
            ->where('date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Convert to a more usable format
        $expensesByDate = [];
        foreach ($dailyExpenses as $expense) {
            $dateKey = Carbon::parse($expense->date)->format('Y-m-d');
            $expensesByDate[$dateKey] = $expense->total;
        }

        // Fill missing dates with 0
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $amount = $expensesByDate[$date] ?? 0;
            
            $chartData[] = [
                'date' => Carbon::parse($date)->format('M d'),
                'amount' => $amount
            ];
        }

        // Recent expenses (last 5)
        $recentExpenses = $user->expenses()
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Monthly trend (last 4 months)
        $monthlyTrend = [];
        for ($i = 3; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $amount = $user->expenses()
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');
            
            $monthlyTrend[] = [
                'month' => $month->format('M'),
                'amount' => $amount,
                'isCurrent' => $i === 0
            ];
        }

        return view('dashboard', compact('stats', 'categoryData', 'chartData', 'recentExpenses', 'budget', 'monthlyTrend'));
    }

    public function updateBudget(Request $request)
    {
        $validated = $request->validate([
            'monthly_budget' => 'required|numeric|min:0|max:999999.99'
        ]);

        $budget = \App\Models\UserBudget::getOrCreateCurrentBudget(Auth::id(), 0);
        $budget->update(['monthly_budget' => $validated['monthly_budget']]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Budget updated successfully!',
                'budget' => [
                    'monthly' => $budget->monthly_budget,
                    'remaining' => max(0, $budget->monthly_budget - Auth::user()->expenses()->thisMonth()->sum('amount'))
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Budget updated successfully!');
    }

    public function adminDashboard(Request $request)
    {
        // Get user statistics for the existing admin dashboard view
        $totalUsers = \App\Models\User::count();
        $adminUsers = \App\Models\User::where('role', 'admin')->count();
        $regularUsers = \App\Models\User::where('role', 'user')->count();
        
        // Get recent users for the dashboard
        $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact('totalUsers', 'adminUsers', 'regularUsers', 'recentUsers'));
    }

    public function adminReports(Request $request)
    {
        // Admin can see all users' data
        $stats = [
            'totalUsers' => \App\Models\User::count(),
            'totalExpenses' => Expense::sum('amount'),
            'thisMonth' => Expense::thisMonth()->sum('amount'),
            'avgUserExpense' => Expense::selectRaw('AVG(user_totals.total) as avg')
                ->fromSub(
                    Expense::selectRaw('user_id, SUM(amount) as total')->groupBy('user_id'),
                    'user_totals'
                )->value('avg') ?? 0
        ];

        // Top categories across all users
        $topCategories = Expense::select('categories.name', DB::raw('SUM(expenses.amount) as total'))
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->thisMonth()
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        // Top spending users
        $topUsers = Expense::select('users.name', DB::raw('SUM(expenses.amount) as total'))
            ->join('users', 'expenses.user_id', '=', 'users.id')
            ->thisMonth()
            ->groupBy('users.id', 'users.name')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports', compact('stats', 'topCategories', 'topUsers'));
    }
}