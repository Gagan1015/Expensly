@extends('layouts.app')

@section('title', 'Expense Reports - Expense Manager')

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-chart-bar me-2"></i>Expense Reports & Analytics
                </h2>
                <p class="text-muted mb-0">Analyze your spending patterns and trends</p>
            </div>
            <div>
                <div class="btn-group" role="group">
                    <a href="{{ route('expenses.export.csv') }}" class="btn btn-outline-success">
                        <i class="fas fa-file-csv me-1"></i>Export CSV
                    </a>
                    <button type="button" class="btn btn-outline-primary" onclick="exportToPDF()">
                        <i class="fas fa-download me-1"></i>Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Period Selector -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label for="reportPeriod" class="form-label">Report Period</label>
                        <select class="form-select" id="reportPeriod">
                            <option value="month" selected>This Month</option>
                            <option value="quarter">This Quarter</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="customDateRange" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate">
                            </div>
                            <div class="col-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-custom w-100" onclick="generateReport()">
                            <i class="fas fa-sync me-1"></i>Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="col-md-3 mb-4">
        <div class="card stats-card text-center h-100">
            <div class="card-body">
                <i class="fas fa-rupee-sign fa-3x text-success mb-3"></i>
                <h5 class="card-title">Total Spent</h5>
                <p class="card-text expense-amount text-success">₹{{ number_format($stats['totalSpent'], 0) }}</p>
                <small class="text-muted">All time</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stats-card text-center h-100">
            <div class="card-body">
                <i class="fas fa-calendar-day fa-3x text-info mb-3"></i>
                <h5 class="card-title">Daily Average</h5>
                <p class="card-text expense-amount text-info">₹{{ number_format($stats['dailyAverage'], 0) }}</p>
                <small class="text-muted">Per day</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stats-card text-center h-100">
            <div class="card-body">
                <i class="fas fa-receipt fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Transactions</h5>
                <p class="card-text expense-amount text-warning">{{ $stats['totalTransactions'] }}</p>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stats-card text-center h-100">
            <div class="card-body">
                <i class="fas fa-chart-line fa-3x text-danger mb-3"></i>
                <h5 class="card-title">Trend</h5>
                <p class="card-text expense-amount {{ $stats['monthlyTrend'] >= 0 ? 'text-danger' : 'text-success' }}">
                    @if($stats['monthlyTrend'] > 0)
                        +{{ number_format($stats['monthlyTrend'], 1) }}%
                    @elseif($stats['monthlyTrend'] < 0)
                        {{ number_format($stats['monthlyTrend'], 1) }}%
                    @else
                        0%
                    @endif
                </p>
                <small class="text-muted">vs last month</small>
            </div>
        </div>
    </div>

    <!-- Spending by Category Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Spending by Category
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px;">
                    @if($categoryBreakdown->count() > 0)
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center w-100">
                                <div class="row">
                                    @foreach($categoryBreakdown as $category)
                                        @php
                                            $percentage = $stats['totalSpent'] > 0 ? ($category->total / $stats['totalSpent']) * 100 : 0;
                                        @endphp
                                        <div class="col-12 mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="badge me-2" style="background-color: {{ $category->color }}; color: white;">
                                                        {{ $category->name }}
                                                    </div>
                                                    <div class="progress flex-grow-1 mx-2" style="height: 10px;">
                                                        <div class="progress-bar" style="width: {{ $percentage }}%; background-color: {{ $category->color }};"></div>
                                                    </div>
                                                </div>
                                                <span class="fw-bold">₹{{ number_format($category->total, 0) }} ({{ number_format($percentage, 1) }}%)</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center text-muted">
                                <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                <h5>No expenses found</h5>
                                <p>Add some expenses to see category breakdown</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Monthly Spending Trend
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px;">
                    @if($monthlyTrend->count() > 0)
                        <div class="d-flex align-items-end justify-content-around h-100 px-3">
                            @php
                                $maxAmount = $monthlyTrend->max('total');
                            @endphp
                            @foreach($monthlyTrend as $index => $month)
                                @php
                                    $height = $maxAmount > 0 ? ($month->total / $maxAmount) * 200 : 0;
                                    $isCurrent = $index === $monthlyTrend->count() - 1;
                                    $barClass = $isCurrent ? 'bg-primary' : 'bg-secondary';
                                @endphp
                                <div class="text-center">
                                    <div class="{{ $barClass }}" style="width: 30px; height: {{ $height }}px; margin-bottom: 10px; border-radius: 4px;"></div>
                                    <small class="fw-bold">{{ $month->month }}</small><br>
                                    <small class="text-muted">₹{{ number_format($month->total, 0) }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center text-muted">
                                <i class="fas fa-chart-line fa-3x mb-3"></i>
                                <h5>No trend data available</h5>
                                <p>Add expenses to see spending trends</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Expenses -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Top 5 Expenses This Month
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($topExpenses as $expense)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($expense->category)
                                        <i class="fas fa-tag fa-lg" style="color: {{ $expense->category->color }};"></i>
                                    @else
                                        <i class="fas fa-question-circle fa-lg text-secondary"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $expense->description ?: 'No description' }}</h6>
                                    <small class="text-muted">{{ $expense->date->format('M d, Y') }} • {{ $expense->category->name ?? 'No category' }}</small>
                                </div>
                            </div>
                            <span class="expense-amount text-danger">₹{{ number_format($expense->amount, 0) }}</span>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-receipt fa-2x mb-2"></i>
                                <p class="mb-0">No expenses found</p>
                                <small>Add some expenses to see them here</small>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Breakdown -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>Payment Methods Usage
                </h5>
            </div>
            <div class="card-body">
                @if($paymentMethods->count() > 0)
                    <div class="row">
                        @php
                            $totalAmount = $paymentMethods->sum('total');
                        @endphp
                        @foreach($paymentMethods as $method)
                            @php
                                $percentage = $totalAmount > 0 ? ($method->total / $totalAmount) * 100 : 0;
                                $methodIcons = [
                                    'cash' => 'fas fa-money-bill',
                                    'credit_card' => 'fas fa-credit-card',
                                    'debit_card' => 'fas fa-university',
                                    'bank_transfer' => 'fas fa-exchange-alt',
                                    'digital_wallet' => 'fas fa-mobile-alt',
                                ];
                                $methodColors = [
                                    'cash' => 'text-warning',
                                    'credit_card' => 'text-primary',
                                    'debit_card' => 'text-success',
                                    'bank_transfer' => 'text-info',
                                    'digital_wallet' => 'text-secondary',
                                ];
                                $progressColors = [
                                    'cash' => 'bg-warning',
                                    'credit_card' => 'bg-primary',
                                    'debit_card' => 'bg-success',
                                    'bank_transfer' => 'bg-info',
                                    'digital_wallet' => 'bg-secondary',
                                ];
                                $icon = $methodIcons[$method->payment_method] ?? 'fas fa-credit-card';
                                $textColor = $methodColors[$method->payment_method] ?? 'text-primary';
                                $progressColor = $progressColors[$method->payment_method] ?? 'bg-primary';
                            @endphp
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $icon }} fa-lg {{ $textColor }} me-2"></i>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ ucwords(str_replace('_', ' ', $method->payment_method)) }}</span>
                                            <span class="fw-bold">{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar {{ $progressColor }}" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-credit-card fa-3x mb-3"></i>
                        <h5>No payment method data</h5>
                        <p>Add expenses with payment methods to see breakdown</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Handle custom date range visibility
    document.getElementById('reportPeriod').addEventListener('change', function() {
        const customRange = document.getElementById('customDateRange');
        if (this.value === 'custom') {
            customRange.style.display = 'block';
        } else {
            customRange.style.display = 'none';
        }
    });

    // Generate report function
    function generateReport() {
        const period = document.getElementById('reportPeriod').value;
        let message = `Generating report for: ${period}`;
        
        if (period === 'custom') {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            if (startDate && endDate) {
                message = `Generating custom report from ${startDate} to ${endDate}`;
                // In a real application, you would redirect or make an AJAX request with these parameters
                window.location.href = `{{ route('expenses.reports') }}?start_date=${startDate}&end_date=${endDate}`;
                return;
            } else {
                alert('Please select both start and end dates for custom range.');
                return;
            }
        }
        
        // For other periods, just reload the page with the period parameter
        window.location.href = `{{ route('expenses.reports') }}?period=${period}`;
    }

    // Export to PDF function
    function exportToPDF() {
        // In a real application, this would generate a PDF report
        alert('PDF export functionality would be implemented here. For now, you can use the CSV export.');
    }

    // Add hover effects to cards
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Animate progress bars on page load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach((bar, index) => {
            setTimeout(() => {
                const width = bar.style.width;
                bar.style.width = '0%';
                bar.style.transition = 'width 1s ease-in-out';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            }, index * 100);
        });
    });
</script>
@endsection
