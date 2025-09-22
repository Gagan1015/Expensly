<?php
// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $categories = Category::forUser(Auth::id())
                             ->withCount('expenses')
                             ->with(['expenses' => function($query) {
                                 $query->where('user_id', Auth::id());
                             }])
                             ->orderBy('name')
                             ->get();

        // Calculate statistics
        $totalCategories = $categories->count();
        $mostUsedCategory = $categories->sortByDesc('expenses_count')->first();
        $highestSpendingCategory = $categories->sortByDesc(function($category) {
            return $category->expenses->sum('amount');
        })->first();

        // Calculate growth (this month vs last month)
        $thisMonthTotal = Auth::user()->expenses()->thisMonth()->sum('amount');
        $lastMonthTotal = Auth::user()->expenses()->lastMonth()->sum('amount');
        $growthPercentage = $lastMonthTotal > 0 ? (($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100 : 0;

        $stats = [
            'totalCategories' => $totalCategories,
            'mostUsed' => $mostUsedCategory,
            'highestSpending' => $highestSpendingCategory,
            'growth' => $growthPercentage,
            'thisMonthTotal' => $thisMonthTotal,
            'lastMonthTotal' => $lastMonthTotal,
        ];

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,user_id,' . Auth::id(),
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'description' => 'nullable|string|max:500'
        ]);

        $validated['user_id'] = Auth::id();

        $category = Category::create($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'color' => $category->color,
                    'description' => $category->description,
                ]
            ]);
        }

        return redirect()->route('categories.index')
                        ->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        
        $category->loadCount(['expenses' => function($query) {
            $query->where('user_id', Auth::id());
        }]);

        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'color' => $category->color,
            'description' => $category->description,
            'expenses_count' => $category->expenses_count,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ]);
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id . ',id,user_id,' . Auth::id(),
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'description' => 'nullable|string|max:500'
        ]);

        $category->update($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'color' => $category->color,
                    'description' => $category->description,
                ]
            ]);
        }

        return redirect()->route('categories.index')
                        ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $expensesCount = $category->expenses()->where('user_id', Auth::id())->count();
        
        if ($expensesCount > 0) {
            // Update expenses to remove category association instead of preventing deletion
            $category->expenses()->where('user_id', Auth::id())->update(['category_id' => null]);
        }

        $category->delete();

        if (request()->expectsJson() || request()->ajax()) {
            $message = $expensesCount > 0 
                ? "Category deleted successfully! {$expensesCount} expenses were updated to remove category association."
                : 'Category deleted successfully!';
                
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        $message = $expensesCount > 0 
            ? "Category deleted successfully! {$expensesCount} expenses were updated to remove category association."
            : 'Category deleted successfully!';

        return redirect()->route('categories.index')
                        ->with('success', $message);
    }

    public function getCategoryData(Category $category)
    {
        // Manual check - ensure the category belongs to the current user or is global
        if ($category->user_id !== Auth::id() && !$category->is_global && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        // Load expenses count for the current user
        $category->loadCount(['expenses' => function($query) {
            $query->where('user_id', Auth::id());
        }]);
        
        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'color' => $category->color,
            'description' => $category->description,
            'expenses_count' => $category->expenses_count,
            'is_global' => $category->is_global,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ]);
    }
}