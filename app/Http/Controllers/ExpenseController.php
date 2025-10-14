<?php
// app/Http/Controllers/ExpenseController.php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = Auth::user()->expenses()->with('category');

        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $expenses = $query->orderBy('date', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->get();

        $categories = Category::forUser(Auth::id())
                             ->orderBy('name')
                             ->get();

        $totalAmount = $query->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalAmount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date|before_or_equal:today',
            'payment_method' => 'nullable|string|max:50',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $category = Category::forUser(Auth::id())->findOrFail($validated['category_id']);

        $validated['user_id'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts/' . Auth::id(), 'public');
            $validated['receipt_path'] = $path;
        }

        $expense = Expense::create($validated);
        $expense->load('category'); // Load category relationship

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Expense added successfully!',
                'expense' => [
                    'id' => $expense->id,
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                    'date' => $expense->date->format('Y-m-d'),
                    'receipt_path' => $expense->receipt_path,
                    'category_name' => $expense->category->name,
                    'category_color' => $expense->category->color,
                ]
            ]);
        }

        return redirect()->route('expenses.index')
                        ->with('success', 'Expense added successfully!');
    }

    public function update(Request $request, Expense $expense)
    {
        // Temporarily disable authorization to test
        // $this->authorize('update', $expense);
        
        // Manual check - ensure the expense belongs to the current user
        if ($expense->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date|before_or_equal:today',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Verify user can use this category
        $category = Category::forUser(Auth::id())->findOrFail($validated['category_id']);

        // Handle file upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }

            $path = $request->file('receipt')->store('receipts/' . Auth::id(), 'public');
            $validated['receipt_path'] = $path;
        }

        $expense->update($validated);
        $expense->load('category'); // Reload category relationship

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully!',
                'expense' => [
                    'id' => $expense->id,
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                    'date' => $expense->date->format('Y-m-d'),
                    'receipt_path' => $expense->receipt_path,
                    'category_name' => $expense->category->name,
                    'category_color' => $expense->category->color,
                ]
            ]);
        }

        return redirect()->route('expenses.index')
                        ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Request $request, Expense $expense)
    {
        // Temporarily disable authorization to test
        // $this->authorize('delete', $expense);
        
        // Manual check - ensure the expense belongs to the current user
        if ($expense->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Delete receipt file if exists
        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully!'
            ]);
        }

        return redirect()->route('expenses.index')
                        ->with('success', 'Expense deleted successfully!');
    }
    
    public function getExpenseData(Expense $expense)
    {
        // Temporarily remove authorization to test if that's the issue
        // $this->authorize('view', $expense);
        
        // Manual check - ensure the expense belongs to the current user
        if ($expense->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        return response()->json([
            'id' => $expense->id,
            'category_id' => $expense->category_id,
            'amount' => $expense->amount,
            'description' => $expense->description,
            'date' => $expense->date->format('Y-m-d'),
            'receipt_path' => $expense->receipt_path,
            'category' => $expense->category ? [
                'name' => $expense->category->name,
                'color' => $expense->category->color
            ] : null
        ]);
    }
    public function exportCsv()
    {
        $expenses = Auth::user()->expenses()
                          ->with('category')
                          ->orderBy('date', 'desc')
                          ->get();

        $filename = 'expenses_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Date', 'Category', 'Amount', 'Description']);
            
            // Add data rows
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->date->format('Y-m-d'),
                    $expense->category->name,
                    $expense->amount,
                    $expense->description
                ]);
            }
            
            fclose($file);
        };

            return response()->stream($callback, 200, $headers);
        }

    public function reports(Request $request)
    {
        $user = Auth::user();
        
        // Basic stats
        $thisMonthAmount = $user->expenses()->thisMonth()->sum('amount');
        $lastMonthAmount = $user->expenses()->lastMonth()->sum('amount');
        
        $stats = [
            'totalSpent' => $user->expenses()->sum('amount'),
            'thisMonth' => $thisMonthAmount,
            'dailyAverage' => $user->expenses()->thisMonth()->avg('amount') ?? 0,
            'totalTransactions' => $user->expenses()->count(),
            'monthlyTrend' => $thisMonthAmount - $lastMonthAmount
        ];

        // Category breakdown
        $categoryBreakdown = $user->expenses()
            ->selectRaw('categories.name, categories.color, SUM(expenses.amount) as total, COUNT(*) as count')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderBy('total', 'desc')
            ->get();

        // Monthly spending trend (last 6 months)
        // Use database-agnostic approach with Laravel's date functions
        $monthlyTrend = $user->expenses()
            ->where('date', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(function($expense) {
                return $expense->date->format('Y-m');
            })
            ->map(function($expenses, $yearMonth) {
                $date = \Carbon\Carbon::parse($yearMonth . '-01');
                return (object) [
                    'year' => $date->year,
                    'month_num' => $date->month,
                    'total' => $expenses->sum('amount'),
                    'month' => $date->format('M')
                ];
            })
            ->sortBy('year')
            ->sortBy('month_num')
            ->values();

        // Top expenses
        $topExpenses = $user->expenses()
            ->with('category')
            ->orderBy('amount', 'desc')
            ->take(5)
            ->get();

        // Payment method breakdown
        $paymentMethods = $user->expenses()
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();

        return view('expenses.reports', compact('stats', 'categoryBreakdown', 'monthlyTrend', 'topExpenses', 'paymentMethods'));
    }
    }
