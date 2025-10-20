@extends('layouts.app')

@section('title', 'Expense Dashboard')

@section('content')
<div class="w-full">
        <!-- Header -->
        <div class="mb-8">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Expense Dashboard</h1>
                <p class="dashboard-subtitle">
                    Welcome back, {{ Auth::user()->name ?? 'Admin' }}! Here's your financial overview for
                    {{ now()->format('F Y') }}
                </p>
            </div>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-6 mb-8">
            <!-- This Month - Large Card -->
            <div class="lg:col-span-2 xl:col-span-2">
                <div class="stat-card h-full">
                    <div class="stat-card-header">
                        <div class="stat-card-title">This Month</div>
                        <div class="stat-icon stat-icon-primary">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-value">₹{{ number_format($stats['thisMonth'] ?? 45000) }}</div>
                        <div class="stat-trend {{ ($stats['monthlyTrend'] ?? 15.2) > 0 ? 'trend-up' : 'trend-down' }}">
                            <i class="fas fa-trending-{{ ($stats['monthlyTrend'] ?? 15.2) > 0 ? 'up' : 'down' }}"></i>
                            {{ abs($stats['monthlyTrend'] ?? 15.2) }}% from last month
                        </div>
                    </div>
                </div>
            </div>

            <!-- This Week -->
            <div class="lg:col-span-1 xl:col-span-1">
                <div class="stat-card h-full">
                    <div class="stat-card-header">
                        <div class="stat-card-title">This Week</div>
                        <div class="stat-icon stat-icon-secondary">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-value">₹{{ number_format($stats['thisWeek'] ?? 12000) }}</div>
                        <div class="stat-trend {{ ($stats['weeklyTrend'] ?? -8.5) > 0 ? 'trend-up' : 'trend-down' }}">
                            <i class="fas fa-trending-{{ ($stats['weeklyTrend'] ?? -8.5) > 0 ? 'up' : 'down' }}"></i>
                            {{ abs($stats['weeklyTrend'] ?? -8.5) }}% from last week
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today -->
            <div class="lg:col-span-1 xl:col-span-1">
                <div class="stat-card h-full">
                    <div class="stat-card-header">
                        <div class="stat-card-title">Today</div>
                        <div class="stat-icon stat-icon-accent">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-value">₹{{ number_format($stats['today'] ?? 2500) }}</div>
                        <div class="stat-trend {{ ($stats['dailyTrend'] ?? 25.0) > 0 ? 'trend-up' : 'trend-down' }}">
                            <i class="fas fa-trending-{{ ($stats['dailyTrend'] ?? 25.0) > 0 ? 'up' : 'down' }}"></i>
                            {{ abs($stats['dailyTrend'] ?? 25.0) }}% from yesterday
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Left - Tall Card -->
            <div class="lg:col-span-2 xl:col-span-2 lg:row-span-2">
                <div class="stat-card h-full">
                    <div class="stat-card-header">
                        <div class="stat-card-title">Budget Overview</div>
                        <div class="stat-icon stat-icon-success">
                            <i class="fas fa-bullseye"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="text-center mb-6">
                            <div class="budget-circle mx-auto" data-percentage="{{ $budget['percentage'] ?? 0 }}">
                                <div class="budget-percentage">{{ number_format($budget['percentage'] ?? 0, 1) }}%</div>
                                <div class="budget-label">Used</div>
                            </div>
                        </div>
                        <div class="stat-value text-center mb-4">₹{{ number_format($budget['remaining'] ?? 15000) }}</div>
                        <div class="text-center text-sm text-gray-500 mb-4">{{ 100 - ($budget['percentage'] ?? 75) }}% remaining</div>
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="budget-stat">
                                <div class="budget-stat-value text-sm">₹{{ number_format($budget['monthly'] ?? 0) }}</div>
                                <div class="budget-stat-label">Total Budget</div>
                            </div>
                            <div class="budget-stat">
                                <div class="budget-stat-value text-sm">₹{{ number_format($budget['used'] ?? 0) }}</div>
                                <div class="budget-stat-label">Used</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Bento Grid Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Charts Section - Large Area -->
            <div class="lg:col-span-2 xl:col-span-3 space-y-6">
                <!-- Chart Tabs -->
                <div class="modern-card" x-data="{ activeTab: 'overview' }">
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8 px-6 pt-6" aria-label="Tabs">
                            <button @click="activeTab = 'overview'" 
                                    :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                                <i class="fas fa-chart-bar mr-2"></i>Overview
                            </button>
                            <button @click="activeTab = 'categories'" 
                                    :class="activeTab === 'categories' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                                <i class="fas fa-chart-pie mr-2"></i>Categories
                            </button>
                        </nav>
                    </div>
                    <div class="p-6">
                        <div x-show="activeTab === 'overview'" x-transition>
                            <h5 class="text-lg font-semibold text-gray-900 mb-2">Monthly Spending Trend</h5>
                            <p class="text-gray-600 mb-4">Your expense patterns over the last 6 months</p>
                            <div class="chart-container">
                                <canvas id="expenseChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                        <div x-show="activeTab === 'categories'" x-transition>
                            <h5 class="text-lg font-semibold text-gray-900 mb-2">Spending by Category</h5>
                            <p class="text-gray-600 mb-4">Breakdown of your expenses this month</p>
                            <div class="chart-container">
                                <canvas id="categoryChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="modern-card">
                    <div class="flex justify-between items-center p-6 border-b border-gray-200">
                        <div>
                            <h5 class="text-lg font-semibold text-gray-900 mb-1">
                                <i class="fas fa-receipt mr-2 text-blue-600"></i>Recent Transactions
                            </h5>
                            <p class="text-gray-600 text-sm">Your latest expense entries</p>
                        </div>
                        <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 transition-colors">
                            View All <i class="fas fa-arrow-up-right ml-1"></i>
                        </a>
                    </div>
                    <div class="p-6">
                    @if(isset($recentExpenses) && count($recentExpenses) > 0)
                    @foreach($recentExpenses as $expense)
                    <div class="transaction-item">
                        <div class="transaction-icon" data-bg-color="{{ $expense->category->color ?? '#3b82f6' }}">
                            <div class="transaction-dot" data-bg-color="{{ $expense->category->color ?? '#3b82f6' }}"></div>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-description">{{ $expense->description ?: 'No description' }}</div>
                            <div class="transaction-meta">
                                <span class="transaction-category">{{ $expense->category->name ?? 'No Category' }}</span>
                                <span class="transaction-date">{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="transaction-amount">₹{{ number_format($expense->amount, 2) }}</div>
                    </div>
                    @endforeach
                    @else
                    <div class="empty-state">
                        <i class="fas fa-receipt fa-3x text-gray-400 mb-3"></i>
                        <h6 class="text-gray-500 font-semibold">No expenses yet</h6>
                        <p class="text-gray-500">Add your first expense to see it here</p>
                    </div>
                    @endif
                    </div>
                </div>
            </div>

            <!-- Right Sidebar - Compact -->
            <div class="lg:col-span-1 xl:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="modern-card">
                    <div class="p-4 border-b border-gray-200">
                        <h5 class="text-base font-semibold text-gray-900">
                            <i class="fas fa-plus mr-2 text-blue-600"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="p-4">
                        <div class="space-y-2">
                            <button class="w-full flex items-center gap-3 p-3 text-left bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" onclick="document.getElementById('budgetModal').classList.remove('hidden')">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-wallet text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900">Set Budget</div>
                                    <div class="text-xs text-gray-500">Monthly budget</div>
                                </div>
                            </button>
                        
                            <a href="{{ route('expenses.index') }}" class="w-full flex items-center gap-3 p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-list text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900">Expenses</div>
                                    <div class="text-xs text-gray-500">Manage</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('expenses.reports') }}" class="w-full flex items-center gap-3 p-3 text-left bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chart-bar text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900">Reports</div>
                                    <div class="text-xs text-gray-500">Analytics</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Top Categories -->
                <div class="modern-card">
                    <div class="p-4 border-b border-gray-200">
                        <h5 class="text-base font-semibold text-gray-900">Top Categories</h5>
                    </div>
                    <div class="p-4">
                    @if(isset($categoryData) && count($categoryData) > 0)
                    @foreach($categoryData->take(3) as $category)
                    <div class="category-item">
                        <div class="category-info">
                            <div class="category-dot" data-bg-color="{{ $category->color }}"></div>
                            <span class="category-name text-sm">{{ $category->name }}</span>
                        </div>
                        <div class="category-stats">
                            <div class="category-amount text-sm">₹{{ number_format($category->total) }}</div>
                            <div class="category-percentage text-xs">{{ $category->count }} expenses</div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="empty-state">
                        <i class="fas fa-tags fa-lg text-gray-400 mb-2"></i>
                        <p class="text-gray-500 text-sm">No categories yet</p>
                    </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Budget Modal -->
<div id="budgetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h5 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-wallet mr-2"></i>Set Monthly Budget
            </h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('budgetModal').classList.add('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="budgetForm" action="{{ route('budget.update') }}" method="POST">
            @csrf
            <div class="py-4">
                <div class="mb-4">
                    <label for="monthly_budget" class="block text-sm font-medium text-gray-700 mb-2">Monthly Budget Amount</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">₹</span>
                        <input type="number" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500" id="monthly_budget" name="monthly_budget"
                            value="{{ $budget['monthly'] ?? 0 }}" min="0" step="0.01" required>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Set your target spending limit for this month</p>
                </div>
                <div class="mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <small class="text-gray-500">Current Used:</small>
                            <div class="font-semibold text-gray-900">₹{{ number_format($budget['used'] ?? 0) }}</div>
                        </div>
                        <div>
                            <small class="text-gray-500">Remaining:</small>
                            <div class="font-semibold text-green-600">₹{{ number_format($budget['remaining'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-3 border-t">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors" onclick="document.getElementById('budgetModal').classList.add('hidden')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Budget
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
<!-- Chart Data for JavaScript -->
<script type="application/json" id="chartData">
{!! json_encode($chartData ?? []) !!}
</script>
<script type="application/json" id="categoryData">
{!! json_encode($categoryData ?? []) !!}
</script>

<!-- Dynamic Styles Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set progress bar widths
    document.querySelectorAll('.progress-bar[data-width]').forEach(function(el) {
        const width = el.getAttribute('data-width');
        el.style.width = width + '%';
    });
    
    // Set background colors for transaction icons and dots
    document.querySelectorAll('[data-bg-color]').forEach(function(el) {
        const color = el.getAttribute('data-bg-color');
        if (el.classList.contains('transaction-icon')) {
            el.style.backgroundColor = color + '20'; // Add transparency
        } else {
            el.style.backgroundColor = color;
        }
    });
    
    // Set budget circle percentage
    document.querySelectorAll('.budget-circle[data-percentage]').forEach(function(el) {
        const percentage = el.getAttribute('data-percentage');
        el.style.setProperty('--percentage', percentage + '%');
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>
@endpush