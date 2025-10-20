@extends('layouts.app')

@section('title', 'Expense Reports - Expense Manager')

@section('content')
<div x-data="reportsData()" class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-chart-bar"></i>
                Expense Reports & Analytics
            </h2>
            <p class="text-gray-600 mt-1">Analyze your spending patterns and trends</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('expenses.export.csv') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                <i class="fas fa-file-csv mr-2"></i>Export CSV
            </a>
            <button @click="exportToPDF" 
                    class="inline-flex items-center px-4 py-2 bg-white border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                <i class="fas fa-download mr-2"></i>Export PDF
            </button>
        </div>
    </div>

    <!-- Time Period Selector -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-3">
                <label for="reportPeriod" class="block text-sm font-medium text-gray-700 mb-2">Report Period</label>
                <select x-model="reportPeriod" 
                        id="reportPeriod"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            <div x-show="reportPeriod === 'custom'" 
                 x-transition
                 class="md:col-span-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" 
                               x-model="startDate"
                               id="startDate"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" 
                               x-model="endDate"
                               id="endDate"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>
            <div class="md:col-span-3">
                <button @click="generateReport" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-sync mr-2"></i>Generate Report
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Spent -->
        <div class="bg-white rounded-lg shadow-sm p-6 text-center transform hover:-translate-y-1 transition-transform duration-300">
            <i class="fas fa-rupee-sign text-5xl text-green-500 mb-3"></i>
            <h5 class="text-gray-600 font-medium mb-2">Total Spent</h5>
            <p class="text-2xl font-bold text-green-600">₹{{ number_format($stats['totalSpent'], 0) }}</p>
            <small class="text-gray-500">All time</small>
        </div>
        
        <!-- Daily Average -->
        <div class="bg-white rounded-lg shadow-sm p-6 text-center transform hover:-translate-y-1 transition-transform duration-300">
            <i class="fas fa-calendar-day text-5xl text-blue-500 mb-3"></i>
            <h5 class="text-gray-600 font-medium mb-2">Daily Average</h5>
            <p class="text-2xl font-bold text-blue-600">₹{{ number_format($stats['dailyAverage'], 0) }}</p>
            <small class="text-gray-500">Per day</small>
        </div>
        
        <!-- Transactions -->
        <div class="bg-white rounded-lg shadow-sm p-6 text-center transform hover:-translate-y-1 transition-transform duration-300">
            <i class="fas fa-receipt text-5xl text-yellow-500 mb-3"></i>
            <h5 class="text-gray-600 font-medium mb-2">Transactions</h5>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['totalTransactions'] }}</p>
            <small class="text-gray-500">Total</small>
        </div>
        
        <!-- Trend -->
        <div class="bg-white rounded-lg shadow-sm p-6 text-center transform hover:-translate-y-1 transition-transform duration-300">
            <i class="fas fa-chart-line text-5xl text-red-500 mb-3"></i>
            <h5 class="text-gray-600 font-medium mb-2">Trend</h5>
            <p class="text-2xl font-bold {{ $stats['monthlyTrend'] >= 0 ? 'text-red-600' : 'text-green-600' }}">
                @if($stats['monthlyTrend'] > 0)
                    +{{ number_format($stats['monthlyTrend'], 1) }}%
                @elseif($stats['monthlyTrend'] < 0)
                    {{ number_format($stats['monthlyTrend'], 1) }}%
                @else
                    0%
                @endif
            </p>
            <small class="text-gray-500">vs last month</small>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Spending by Category Chart -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-chart-pie"></i>Spending by Category
                </h5>
            </div>
            <div class="p-6">
                <div class="min-h-[300px] flex items-center justify-center">
                    @if($categoryBreakdown->count() > 0)
                        <div class="w-full space-y-4">
                            @foreach($categoryBreakdown as $category)
                                @php
                                    $percentage = $stats['totalSpent'] > 0 ? ($category->total / $stats['totalSpent']) * 100 : 0;
                                @endphp
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="px-3 py-1 rounded-full text-white text-sm font-medium" 
                                                  x-data
                                                  x-init="$el.style.backgroundColor = '{{ $category->color }}'">
                                                {{ $category->name }}
                                            </span>
                                        </div>
                                        <span class="font-bold text-gray-900">₹{{ number_format($category->total, 0) }} ({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full transition-all duration-1000" 
                                             x-data="{ percentage: {{ $percentage }}, color: '{{ $category->color }}' }"
                                             x-init="setTimeout(() => { $el.style.width = percentage + '%'; $el.style.backgroundColor = color; }, 100)"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-400">
                            <i class="fas fa-chart-pie text-5xl mb-3"></i>
                            <h5 class="text-lg font-medium text-gray-600">No expenses found</h5>
                            <p class="text-sm">Add some expenses to see category breakdown</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-chart-line"></i>Monthly Spending Trend
                </h5>
            </div>
            <div class="p-6">
                <div class="min-h-[300px] flex items-end justify-around px-4">
                    @if($monthlyTrend->count() > 0)
                        @php
                            $maxAmount = $monthlyTrend->max('total');
                        @endphp
                        @foreach($monthlyTrend as $index => $month)
                            @php
                                $height = $maxAmount > 0 ? ($month->total / $maxAmount) * 200 : 0;
                                $isCurrent = $index === $monthlyTrend->count() - 1;
                                $barClass = $isCurrent ? 'bg-blue-600' : 'bg-gray-400';
                            @endphp
                            <div class="text-center">
                                <div class="{{ $barClass }} w-8 rounded-t transition-all duration-500 mb-2" 
                                     x-data="{ height: {{ $height }}, delay: {{ $index * 100 }} }"
                                     x-init="setTimeout(() => $el.style.height = height + 'px', delay)"></div>
                                <small class="font-bold text-gray-700 block">{{ $month->month }}</small>
                                <small class="text-gray-500 block">₹{{ number_format($month->total, 0) }}</small>
                            </div>
                        @endforeach
                    @else
                        <div class="w-full flex justify-center items-center h-[300px]">
                            <div class="text-center text-gray-400">
                                <i class="fas fa-chart-line text-5xl mb-3"></i>
                                <h5 class="text-lg font-medium text-gray-600">No trend data available</h5>
                                <p class="text-sm">Add expenses to see spending trends</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Expenses -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-trophy"></i>Top 5 Expenses This Month
                </h5>
            </div>
            <div>
                @forelse($topExpenses as $expense)
                    <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div>
                                @if($expense->category)
                                    <i class="fas fa-tag text-2xl" 
                                       x-data 
                                       x-init="$el.style.color = '{{ $expense->category->color }}'"></i>
                                @else
                                    <i class="fas fa-question-circle text-2xl text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="font-medium text-gray-900">{{ $expense->description ?: 'No description' }}</h6>
                                <small class="text-gray-500">{{ $expense->date->format('M d, Y') }} • {{ $expense->category->name ?? 'No category' }}</small>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-red-600">₹{{ number_format($expense->amount, 0) }}</span>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-receipt text-4xl mb-2"></i>
                            <p class="font-medium text-gray-600">No expenses found</p>
                            <small class="text-sm">Add some expenses to see them here</small>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-credit-card"></i>Payment Methods Usage
                </h5>
            </div>
            <div class="p-6">
                @if($paymentMethods->count() > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $totalAmount = $paymentMethods->sum('total');
                            $methodIcons = [
                                'cash' => 'fas fa-money-bill',
                                'credit_card' => 'fas fa-credit-card',
                                'debit_card' => 'fas fa-university',
                                'bank_transfer' => 'fas fa-exchange-alt',
                                'digital_wallet' => 'fas fa-mobile-alt',
                            ];
                            $methodColors = [
                                'cash' => 'text-yellow-500',
                                'credit_card' => 'text-blue-500',
                                'debit_card' => 'text-green-500',
                                'bank_transfer' => 'text-cyan-500',
                                'digital_wallet' => 'text-gray-500',
                            ];
                            $progressColors = [
                                'cash' => 'bg-yellow-500',
                                'credit_card' => 'bg-blue-500',
                                'debit_card' => 'bg-green-500',
                                'bank_transfer' => 'bg-cyan-500',
                                'digital_wallet' => 'bg-gray-500',
                            ];
                        @endphp
                        @foreach($paymentMethods as $method)
                            @php
                                $percentage = $totalAmount > 0 ? ($method->total / $totalAmount) * 100 : 0;
                                $icon = $methodIcons[$method->payment_method] ?? 'fas fa-credit-card';
                                $textColor = $methodColors[$method->payment_method] ?? 'text-blue-500';
                                $progressColor = $progressColors[$method->payment_method] ?? 'bg-blue-500';
                            @endphp
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <i class="{{ $icon }} text-xl {{ $textColor }}"></i>
                                    <div class="flex-1">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700">{{ ucwords(str_replace('_', ' ', $method->payment_method)) }}</span>
                                            <span class="font-bold text-gray-900">{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="{{ $progressColor }} h-1.5 rounded-full transition-all duration-1000" 
                                                 x-data="{ percentage: {{ $percentage }} }"
                                                 x-init="setTimeout(() => $el.style.width = percentage + '%', 100)"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-400 py-8">
                        <i class="fas fa-credit-card text-5xl mb-3"></i>
                        <h5 class="text-lg font-medium text-gray-600">No payment method data</h5>
                        <p class="text-sm">Add expenses with payment methods to see breakdown</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function reportsData() {
    return {
        reportPeriod: 'month',
        startDate: '',
        endDate: '',
        
        generateReport() {
            if (this.reportPeriod === 'custom') {
                if (!this.startDate || !this.endDate) {
                    alert('Please select both start and end dates for custom range.');
                    return;
                }
                window.location.href = `{{ route('expenses.reports') }}?start_date=${this.startDate}&end_date=${this.endDate}`;
            } else {
                window.location.href = `{{ route('expenses.reports') }}?period=${this.reportPeriod}`;
            }
        },
        
        exportToPDF() {
            alert('PDF export functionality would be implemented here. For now, you can use the CSV export.');
        }
    }
}
</script>
@endsection