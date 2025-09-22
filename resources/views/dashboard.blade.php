@extends('layouts.app')

@section('title', 'Expense Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Expense Dashboard</h1>
                <p class="dashboard-subtitle">
                    Welcome back, {{ Auth::user()->name ?? 'Admin' }}! Here's your financial overview for
                    {{ now()->format('F Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
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

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
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

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
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

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-title">Budget Left</div>
                    <div class="stat-icon stat-icon-success">
                        <i class="fas fa-bullseye"></i>
                    </div>
                </div>
                <div class="stat-card-body">
                    <div class="stat-value">₹{{ number_format($budget['remaining'] ?? 15000) }}</div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">{{ 100 - ($budget['percentage'] ?? 75) }}% remaining</small>
                        <span class="badge badge-{{ ($budget['percentage'] ?? 75) > 80 ? 'danger' : (($budget['percentage'] ?? 75) > 60 ? 'warning' : 'success') }}">
                            {{ $budget['percentage'] ?? 75 }}% used
                        </span>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $budget['percentage'] ?? 75 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Charts Section -->
        <div class="col-lg-8">
            <!-- Chart Tabs -->
            <div class="modern-card mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="chartTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fas fa-chart-bar me-2"></i>Overview
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">
                                <i class="fas fa-chart-pie me-2"></i>Categories
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="chartTabsContent">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <h5 class="card-title">Monthly Spending Trend</h5>
                            <p class="card-text text-muted">Your expense patterns over the last 6 months</p>
                            <div class="chart-container">
                                <canvas id="expenseChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="categories" role="tabpanel">
                            <h5 class="card-title">Spending by Category</h5>
                            <p class="card-text text-muted">Breakdown of your expenses this month</p>
                            <div class="chart-container">
                                <canvas id="categoryChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="modern-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="fas fa-receipt me-2 text-primary"></i>Recent Transactions
                        </h5>
                        <p class="card-text text-muted mb-0">Your latest expense entries</p>
                    </div>
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-primary btn-sm">
                        View All <i class="fas fa-arrow-up-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentExpenses) && count($recentExpenses) > 0)
                    @foreach($recentExpenses as $expense)
                    <div class="transaction-item">
                        <div class="transaction-icon" style="background-color: {{ $expense->category->color ?? '#3b82f6' }}20;">
                            <div class="transaction-dot" style="background-color: {{ $expense->category->color ?? '#3b82f6' }};"></div>
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
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No expenses yet</h6>
                        <p class="text-muted">Add your first expense to see it here</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="modern-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2 text-primary"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <button class="quick-action-btn primary" data-bs-toggle="modal" data-bs-target="#budgetModal">
                            <div class="quick-action-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Set Budget</div>
                                <div class="quick-action-subtitle">Manage monthly budget</div>
                            </div>
                        </button>
                        
                        <a href="{{ route('expenses.index') }}" class="quick-action-btn secondary">
                            <div class="quick-action-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Manage Expenses</div>
                                <div class="quick-action-subtitle">Add & edit expenses</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('expenses.reports') }}" class="quick-action-btn success">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">View Reports</div>
                                <div class="quick-action-subtitle">Expense analytics</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('categories.index') }}" class="quick-action-btn warning">
                            <div class="quick-action-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Manage Categories</div>
                                <div class="quick-action-subtitle">Organize expenses</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Budget Overview -->
            <div class="modern-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-1">
                        <i class="fas fa-bullseye me-2 text-primary"></i>Budget Overview
                    </h5>
                    <p class="card-text text-muted mb-0">{{ now()->format('F Y') }} budget status</p>
                </div>
                <div class="card-body text-center">
                    <div class="budget-circle" style="--percentage: {{ $budget['percentage'] }}">
                        <div class="budget-percentage">{{ number_format($budget['percentage'], 1) }}%</div>
                        <div class="budget-label">Used</div>
                    </div>
                    <div class="budget-details mt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="budget-stat">
                                    <div class="budget-stat-value">₹{{ number_format($budget['monthly']) }}</div>
                                    <div class="budget-stat-label">Total Budget</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="budget-stat">
                                    <div class="budget-stat-value">₹{{ number_format($budget['used']) }}</div>
                                    <div class="budget-stat-label">Used</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Categories -->
            <div class="modern-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Categories</h5>
                </div>
                <div class="card-body">
                    @if(isset($categoryData) && count($categoryData) > 0)
                    @foreach($categoryData->take(3) as $category)
                    <div class="category-item">
                        <div class="category-info">
                            <div class="category-dot" style="background-color: {{ $category->color }};"></div>
                            <span class="category-name">{{ $category->name }}</span>
                        </div>
                        <div class="category-stats">
                            <div class="category-amount">₹{{ number_format($category->total) }}</div>
                            <div class="category-percentage">{{ $category->count }} expenses</div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="empty-state">
                        <i class="fas fa-tags fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No categories with expenses yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Budget Modal -->
<div class="modal fade" id="budgetModal" tabindex="-1" aria-labelledby="budgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="budgetModalLabel">
                    <i class="fas fa-wallet me-2"></i>Set Monthly Budget
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="budgetForm" action="{{ route('budget.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="monthly_budget" class="form-label">Monthly Budget Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" id="monthly_budget" name="monthly_budget"
                                value="{{ $budget['monthly'] }}" min="0" step="0.01" required>
                        </div>
                        <div class="form-text">Set your target spending limit for this month</div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Current Used:</small>
                                <div class="fw-bold">₹{{ number_format($budget['used']) }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Remaining:</small>
                                <div class="fw-bold text-success">₹{{ number_format($budget['remaining']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Budget
                    </button>
                </div>
            </form>
        </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>
@endpush