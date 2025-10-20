@extends('layouts.app')

@section('title', 'My Expenses - Expense Manager')

@section('content')
<div x-data="expenseManager()" class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-4 md:p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2 flex items-center gap-2">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    My Expenses
                </h2>
                <p class="text-slate-600">Manage and track all your expenses</p>
            </div>
            <button @click="openAddModal()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Expense
            </button>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
                <label for="dateFilter" class="block text-sm font-semibold text-slate-700 mb-2">Date Range</label>
                <select @change="filterExpenses()" x-model="filters.dateRange" id="dateFilter" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            <div>
                <label for="categoryFilter" class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                <select @change="filterExpenses()" x-model="filters.category" id="categoryFilter" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="searchInput" class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                <input @input="filterExpenses()" x-model="filters.search" type="text" id="searchInput" placeholder="Search descriptions..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">&nbsp;</label>
                <button @click="clearFilters()" class="w-full px-4 py-2 border-2 border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Custom Date Range -->
        <div x-show="filters.dateRange === 'custom'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
            <div>
                <label for="dateFrom" class="block text-sm font-semibold text-slate-700 mb-2">From Date</label>
                <input @change="filterExpenses()" x-model="filters.dateFrom" type="date" id="dateFrom" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>
            <div>
                <label for="dateTo" class="block text-sm font-semibold text-slate-700 mb-2">To Date</label>
                <input @change="filterExpenses()" x-model="filters.dateTo" type="date" id="dateTo" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-sm font-semibold text-slate-600">Total</h5>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0015.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-blue-600">₹<span x-text="formatCurrency(stats.total)"></span></p>
            <p class="text-sm text-slate-500 mt-2"><span x-text="stats.count"></span> expenses</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-sm font-semibold text-slate-600">Average</h5>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-green-600">₹<span x-text="formatCurrency(stats.average)"></span></p>
            <p class="text-sm text-slate-500 mt-2">Per expense</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-sm font-semibold text-slate-600">Categories</h5>
                <div class="p-3 bg-amber-100 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 000-2H7zM4 7a1 1 0 011-1h10a1 1 0 011 1v3a2 2 0 01-2 2H6a2 2 0 01-2-2V7z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-amber-600">{{ $categories->count() }}</p>
            <p class="text-sm text-slate-500 mt-2">Available</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-sm font-semibold text-slate-600">This Month</h5>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-red-600">₹<span x-text="formatCurrency(stats.thisMonth)"></span></p>
            <p class="text-sm text-slate-500 mt-2"><span x-text="stats.thisMonthCount"></span> expenses</p>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h5 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                Expense List
            </h5>
            <div class="flex gap-2">
                <a href="{{ route('expenses.export.csv') }}" class="px-4 py-2 border border-green-300 text-green-700 font-semibold rounded-lg hover:bg-green-50 transition-all duration-200 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('expenses.reports') }}" class="px-4 py-2 border border-blue-300 text-blue-700 font-semibold rounded-lg hover:bg-blue-50 transition-all duration-200 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Reports
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Category</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Amount</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Receipt</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr data-expense-id="{{ $expense->id }}" class="border-b border-slate-200 hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $expense->date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($expense->category)
                                <span class="inline-block px-3 py-1 rounded-full text-white text-xs font-semibold" {!! 'style="background-color:' . $expense->category->color . '"' !!}>
                                    {{ $expense->category->name }}
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-white text-xs font-semibold bg-slate-400">No Category</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $expense->description ?: 'No description' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-blue-600">₹{{ number_format($expense->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($expense->receipt_path)
                                <button @click="window.expenseManagerInstance.previewReceipt('{{ Storage::url($expense->receipt_path) }}', '{{ addslashes($expense->description ?? '') }}')" class="px-3 py-1 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200 text-sm font-semibold">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            @else
                                <span class="text-slate-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex justify-center gap-2">
                                <button @click="window.expenseManagerInstance.viewExpense({{ $expense->id }})" class="p-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button @click="window.expenseManagerInstance.editExpense({{ $expense->id }})" class="p-2 border border-amber-300 text-amber-600 rounded-lg hover:bg-amber-50 transition-all duration-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button @click="window.expenseManagerInstance.deleteExpense({{ $expense->id }}, '{{ addslashes($expense->description ?? 'No description') }}', '₹{{ number_format($expense->amount, 2) }}', '{{ $expense->date->format('M d, Y') }}')" class="p-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h5 class="text-lg font-semibold text-slate-600 mb-2">No expenses found</h5>
                                <p class="text-slate-500">Start by adding your first expense!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div x-show="modals.add" x-transition class="fixed inset-0 bg-white/10 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="modals.add = false">
        <div x-transition class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
                <h5 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Expense
                </h5>
                <button @click="modals.add = false" class="text-white hover:text-blue-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form @submit.prevent="submitAddExpense()" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="add_category_id" class="block text-sm font-semibold text-slate-700 mb-2">Category <span class="text-red-500">*</span></label>
                        <select x-model="form.add.category_id" id="add_category_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="add_amount" class="block text-sm font-semibold text-slate-700 mb-2">Amount <span class="text-red-500">*</span></label>
                        <input x-model.number="form.add.amount" type="number" id="add_amount" step="0.01" min="0.01" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label for="add_date" class="block text-sm font-semibold text-slate-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input x-model="form.add.date" type="date" id="add_date" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label for="add_receipt" class="block text-sm font-semibold text-slate-700 mb-2">Receipt (Optional)</label>
                        <input @change="form.add.receipt = $event.target.files[0]" type="file" id="add_receipt" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div class="md:col-span-2">
                        <label for="add_description" class="block text-sm font-semibold text-slate-700 mb-2">Description (Optional)</label>
                        <textarea x-model="form.add.description" id="add_description" rows="3" placeholder="Enter expense description..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 justify-end pt-4 border-t border-slate-200">
                    <button type="button" @click="modals.add = false" class="px-6 py-2 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-all duration-200">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4m-1 0V7m1 0V5m0 0h6m-6 0l1 1m-1-1l-1 1"/>
                        </svg>
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <div x-show="modals.edit" x-transition class="fixed inset-0 bg-white/10 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="modals.edit = false">
        <div x-transition class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-amber-600 text-white px-6 py-4 flex justify-between items-center">
                <h5 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Expense
                </h5>
                <button @click="modals.edit = false" class="text-white hover:text-amber-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form @submit.prevent="submitEditExpense()" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="edit_category_id" class="block text-sm font-semibold text-slate-700 mb-2">Category <span class="text-red-500">*</span></label>
                        <select x-model="form.edit.category_id" id="edit_category_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edit_amount" class="block text-sm font-semibold text-slate-700 mb-2">Amount <span class="text-red-500">*</span></label>
                        <input x-model.number="form.edit.amount" type="number" id="edit_amount" step="0.01" min="0.01" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label for="edit_date" class="block text-sm font-semibold text-slate-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input x-model="form.edit.date" type="date" id="edit_date" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label for="edit_receipt" class="block text-sm font-semibold text-slate-700 mb-2">Receipt (Optional)</label>
                        <input @change="form.edit.receipt = $event.target.files[0]" type="file" id="edit_receipt" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div class="md:col-span-2">
                        <label for="edit_description" class="block text-sm font-semibold text-slate-700 mb-2">Description (Optional)</label>
                        <textarea x-model="form.edit.description" id="edit_description" rows="3" placeholder="Enter expense description..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"></textarea>
                    </div>
                </div>
                <div x-show="form.edit.currentReceipt" class="mb-4 p-3 bg-slate-100 rounded-lg">
                    <small class="text-slate-600">Current receipt: <a :href="form.edit.currentReceipt" target="_blank" class="text-blue-600 hover:underline">View</a></small>
                </div>
                <div class="flex gap-3 justify-end pt-4 border-t border-slate-200">
                    <button type="button" @click="modals.edit = false" class="px-6 py-2 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-all duration-200">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4m-1 0V7m1 0V5m0 0h6m-6 0l1 1m-1-1l-1 1"/>
                        </svg>
                        Update Expense
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Expense Modal -->
    <div x-show="modals.view" x-transition class="fixed inset-0 bg-white/10 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="modals.view = false">
        <div x-transition class="bg-white rounded-xl shadow-2xl max-w-2xl w-full">
            <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
                <h5 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Expense Details
                </h5>
                <button @click="modals.view = false" class="text-white hover:text-blue-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <strong class="text-slate-700 block mb-2">Amount</strong>
                        <p class="text-3xl font-bold text-blue-600">₹<span x-text="form.view.amount"></span></p>
                    </div>
                    <div>
                        <strong class="text-slate-700 block mb-2">Date</strong>
                        <p class="text-lg text-slate-900" x-text="form.view.date"></p>
                    </div>
                    <div>
                        <strong class="text-slate-700 block mb-2">Category</strong>
                        <p x-html="form.view.category"></p>
                    </div>
                    <div>
                        <strong class="text-slate-700 block mb-2">Receipt</strong>
                        <div x-html="form.view.receipt"></div>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-slate-700 block mb-2">Description</strong>
                        <p class="text-slate-700" x-text="form.view.description"></p>
                    </div>
                </div>
                <div class="flex gap-3 justify-end pt-4 border-t border-slate-200">
                    <button @click="modals.view = false" class="px-6 py-2 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-all duration-200">Close</button>
                    <button @click="editFromView()" class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Expense
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="modals.delete" x-transition class="fixed inset-0 bg-white/10 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="modals.delete = false">
        <div x-transition class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="bg-red-600 text-white px-6 py-4 flex justify-between items-center">
                <h5 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-2h2m-2 0h-2m6-4h.01M9 16h.01M15 16h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Confirm Delete
                </h5>
                <button @click="modals.delete = false" class="text-white hover:text-red-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <h5 class="text-lg font-semibold text-slate-900 mb-2">Are you sure?</h5>
                    <p class="text-slate-600 mb-4">This action cannot be undone.</p>
                </div>
                <div class="bg-slate-100 rounded-lg p-4 mb-6 space-y-2">
                    <div><strong class="text-slate-700">Amount: </strong><span x-text="form.delete.amount" class="text-slate-900"></span></div>
                    <div><strong class="text-slate-700">Description: </strong><span x-text="form.delete.description" class="text-slate-900"></span></div>
                    <div><strong class="text-slate-700">Date: </strong><span x-text="form.delete.date" class="text-slate-900"></span></div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button @click="modals.delete = false" class="px-6 py-2 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </button>
                    <button @click="submitDelete()" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Expense
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Preview Modal -->
    <div x-show="modals.receipt" x-transition class="fixed inset-0 bg-white/10 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="modals.receipt = false">
        <div x-transition class="bg-white rounded-xl shadow-2xl max-w-2xl w-full">
            <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
                <h5 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Receipt Preview
                </h5>
                <button @click="modals.receipt = false" class="text-white hover:text-blue-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 text-center">
                <img :src="form.receipt.url" :alt="form.receipt.description" class="max-h-96 max-w-full mx-auto rounded-lg shadow-md mb-4">
                <p class="text-slate-600 mb-6" x-text="form.receipt.description"></p>
                <div class="flex gap-3 justify-center">
                    <button @click="modals.receipt = false" class="px-6 py-2 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-all duration-200">Close</button>
                    <a :href="form.receipt.url" download class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                    <a :href="form.receipt.url" target="_blank" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Open in New Tab
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function expenseManager() {
    return {
        modals: {
            add: false,
            edit: false,
            view: false,
            delete: false,
            receipt: false
        },
        filters: {
            dateRange: 'month',
            category: 'all',
            search: '',
            dateFrom: '',
            dateTo: ''
        },
        form: {
            add: {
                category_id: '',
                amount: '',
                date: new Date().toISOString().split('T')[0],
                receipt: null,
                description: ''
            },
            edit: {
                id: null,
                category_id: '',
                amount: '',
                date: '',
                receipt: null,
                description: '',
                currentReceipt: ''
            },
            view: {
                amount: '',
                date: '',
                category: '',
                receipt: '',
                description: ''
            },
            delete: {
                id: null,
                amount: '',
                description: '',
                date: ''
            },
            receipt: {
                url: '',
                description: ''
            }
        },
        stats: {
            total: 0,
            average: 0,
            count: 0,
            thisMonth: 0,
            thisMonthCount: 0
        },

        openAddModal() {
            this.form.add = {
                category_id: '',
                amount: '',
                date: new Date().toISOString().split('T')[0],
                receipt: null,
                description: ''
            };
            this.modals.add = true;
        },

        async submitAddExpense() {
            const formData = new FormData();
            formData.append('category_id', this.form.add.category_id);
            formData.append('amount', this.form.add.amount);
            formData.append('date', this.form.add.date);
            formData.append('description', this.form.add.description);
            if (this.form.add.receipt) {
                formData.append('receipt', this.form.add.receipt);
            }

            try {
                const response = await fetch('{{ route("expenses.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showToast('Expense added successfully!', 'success');
                    this.modals.add = false;
                    this.addExpenseToTable(data.expense);
                    this.updateStats();
                } else {
                    this.showToast(data.message || 'Error adding expense', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showToast('Error adding expense. Please try again.', 'error');
            }
        },

        async editExpense(expenseId) {
            try {
                const response = await fetch(`/api/expenses/${expenseId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                this.form.edit = {
                    id: data.id,
                    category_id: data.category_id,
                    amount: data.amount,
                    date: data.date,
                    receipt: null,
                    description: data.description || '',
                    currentReceipt: data.receipt_path ? `/storage/${data.receipt_path}` : ''
                };
                this.modals.edit = true;
            } catch (error) {
                console.error('Error:', error);
                this.showToast('Error loading expense data', 'error');
            }
        },

        async submitEditExpense() {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('category_id', this.form.edit.category_id);
            formData.append('amount', this.form.edit.amount);
            formData.append('date', this.form.edit.date);
            formData.append('description', this.form.edit.description);
            if (this.form.edit.receipt) {
                formData.append('receipt', this.form.edit.receipt);
            }

            try {
                const response = await fetch(`/expenses/${this.form.edit.id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showToast('Expense updated successfully!', 'success');
                    this.modals.edit = false;
                    this.updateExpenseInTable(data.expense);
                    this.updateStats();
                } else {
                    this.showToast(data.message || 'Error updating expense', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showToast('Error updating expense. Please try again.', 'error');
            }
        },

        async viewExpense(expenseId) {
            try {
                const response = await fetch(`/api/expenses/${expenseId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                this.form.view = {
                    amount: parseFloat(data.amount).toFixed(2),
                    date: new Date(data.date).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }),
                    category: data.category ? `<span class="inline-block px-3 py-1 rounded-full text-white text-xs font-semibold" style="background-color: ${data.category.color};">${data.category.name}</span>` : '<span class="inline-block px-3 py-1 rounded-full text-white text-xs font-semibold bg-slate-400">No Category</span>',
                    receipt: data.receipt_path ? `<button onclick="document.querySelector('[x-data]').__x.$data.previewReceipt('/storage/${data.receipt_path}', '${data.description}')" class="px-3 py-1 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200 text-sm font-semibold"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> View Receipt</button>` : '<span class="text-slate-400 text-sm">No receipt uploaded</span>',
                    description: data.description || 'No description provided'
                };
                this.modals.view = true;
            } catch (error) {
                console.error('Error:', error);
                this.showToast('Error loading expense details', 'error');
            }
        },

        editFromView() {
            this.modals.view = false;
            setTimeout(() => {
                this.editExpense(this.form.view.id);
            }, 300);
        },

        deleteExpense(expenseId, description, amount, date) {
            this.form.delete = {
                id: expenseId,
                amount: amount,
                description: description || 'No description',
                date: date
            };
            this.modals.delete = true;
        },

        async submitDelete() {
            try {
                const response = await fetch(`/expenses/${this.form.delete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showToast('Expense deleted successfully!', 'success');
                    document.querySelector(`tr[data-expense-id="${this.form.delete.id}"]`).remove();
                    this.modals.delete = false;
                    this.updateStats();
                } else {
                    this.showToast('Error deleting expense', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showToast('Error deleting expense. Please try again.', 'error');
            }
        },

        previewReceipt(url, description) {
            this.form.receipt = {
                url: url,
                description: description || 'Receipt'
            };
            this.modals.receipt = true;
        },

        clearFilters() {
            this.filters = {
                dateRange: 'month',
                category: 'all',
                search: '',
                dateFrom: '',
                dateTo: ''
            };
            this.filterExpenses();
        },

        filterExpenses() {
            // Client-side filtering logic
            const table = document.getElementById('expensesTable') || document.querySelector('table');
            if (!table) return;

            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                let show = true;

                // Date filter
                if (this.filters.dateRange !== 'all') {
                    const dateCell = row.cells[0];
                    if (dateCell) {
                        const expenseDate = new Date(dateCell.textContent.trim());
                        const now = new Date();

                        if (!isNaN(expenseDate.getTime())) {
                            let showByDate = false;

                            switch (this.filters.dateRange) {
                                case 'today':
                                    showByDate = this.isSameDay(expenseDate, now);
                                    break;
                                case 'week':
                                    const weekStart = new Date(now);
                                    weekStart.setDate(now.getDate() - now.getDay());
                                    weekStart.setHours(0, 0, 0, 0);
                                    showByDate = expenseDate >= weekStart && expenseDate <= now;
                                    break;
                                case 'month':
                                    showByDate = expenseDate.getMonth() === now.getMonth() &&
                                        expenseDate.getFullYear() === now.getFullYear();
                                    break;
                                case 'quarter':
                                    const currentQuarter = Math.floor(now.getMonth() / 3);
                                    const expenseQuarter = Math.floor(expenseDate.getMonth() / 3);
                                    showByDate = expenseQuarter === currentQuarter &&
                                        expenseDate.getFullYear() === now.getFullYear();
                                    break;
                                case 'year':
                                    showByDate = expenseDate.getFullYear() === now.getFullYear();
                                    break;
                                case 'custom':
                                    if (this.filters.dateFrom && this.filters.dateTo) {
                                        const from = new Date(this.filters.dateFrom);
                                        const to = new Date(this.filters.dateTo);
                                        to.setHours(23, 59, 59, 999);
                                        showByDate = expenseDate >= from && expenseDate <= to;
                                    } else {
                                        showByDate = true;
                                    }
                                    break;
                                default:
                                    showByDate = true;
                            }

                            if (!showByDate) {
                                show = false;
                            }
                        }
                    }
                }

                // Category filter
                if (show && this.filters.category !== 'all') {
                    const categoryCell = row.cells[1];
                    if (categoryCell && !categoryCell.textContent.includes(this.filters.category)) {
                        show = false;
                    }
                }

                // Search filter
                if (show && this.filters.search) {
                    const descriptionCell = row.cells[2];
                    if (descriptionCell && !descriptionCell.textContent.toLowerCase().includes(this.filters.search.toLowerCase())) {
                        show = false;
                    }
                }

                row.style.display = show ? '' : 'none';
            });

            this.updateStats();
        },

        isSameDay(date1, date2) {
            return date1.getDate() === date2.getDate() &&
                date1.getMonth() === date2.getMonth() &&
                date1.getFullYear() === date2.getFullYear();
        },

        updateStats() {
            const table = document.querySelector('table');
            if (!table) return;

            const rows = table.querySelectorAll('tbody tr');
            let total = 0;
            let count = 0;
            let thisMonthTotal = 0;
            let thisMonthCount = 0;
            const now = new Date();

            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const amountCell = row.cells[3];
                    const dateCell = row.cells[0];

                    if (amountCell) {
                        const amountText = amountCell.textContent.replace(/[₹,]/g, '');
                        const amount = parseFloat(amountText);

                        if (!isNaN(amount)) {
                            total += amount;
                            count++;

                            if (dateCell) {
                                const expenseDate = new Date(dateCell.textContent.trim());
                                if (expenseDate.getMonth() === now.getMonth() &&
                                    expenseDate.getFullYear() === now.getFullYear()) {
                                    thisMonthTotal += amount;
                                    thisMonthCount++;
                                }
                            }
                        }
                    }
                }
            });

            this.stats = {
                total: total,
                average: count > 0 ? total / count : 0,
                count: count,
                thisMonth: thisMonthTotal,
                thisMonthCount: thisMonthCount
            };
        },

        formatCurrency(value) {
            return parseFloat(value).toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        },

        addExpenseToTable(expense) {
            const tbody = document.querySelector('table tbody');
            const noExpensesRow = tbody.querySelector('tr td[colspan="6"]');
            if (noExpensesRow) {
                noExpensesRow.closest('tr').remove();
            }

            const date = new Date(expense.date);
            const formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            
            const safeDesc = (expense.description || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');

            const newRow = document.createElement('tr');
            newRow.setAttribute('data-expense-id', expense.id);
            newRow.className = 'border-b border-slate-200 hover:bg-slate-50 transition-colors duration-150';
            newRow.innerHTML = `
                <td class="px-6 py-4 text-sm text-slate-900">${formattedDate}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-block px-3 py-1 rounded-full text-white text-xs font-semibold" style="background-color: ${expense.category_color};">
                        ${expense.category_name}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-slate-700">${expense.description || 'No description'}</td>
                <td class="px-6 py-4 text-sm font-semibold text-blue-600">₹${parseFloat(expense.amount).toFixed(2)}</td>
                <td class="px-6 py-4 text-sm">
                    ${expense.receipt_path ? 
                        `<button onclick="window.expenseManagerInstance.previewReceipt('/storage/${expense.receipt_path}', '${safeDesc}')" class="px-3 py-1 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200 text-sm font-semibold">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </button>` : 
                        '<span class="text-slate-400 text-sm">-</span>'
                    }
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex justify-center gap-2">
                        <button onclick="window.expenseManagerInstance.viewExpense(${expense.id})" class="p-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200" title="View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button onclick="window.expenseManagerInstance.editExpense(${expense.id})" class="p-2 border border-amber-300 text-amber-600 rounded-lg hover:bg-amber-50 transition-all duration-200" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="window.expenseManagerInstance.deleteExpense(${expense.id}, '${safeDesc}', '₹${parseFloat(expense.amount).toFixed(2)}', '${formattedDate}')" class="p-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            `;

            tbody.insertBefore(newRow, tbody.firstChild);
        },

        updateExpenseInTable(expense) {
            const row = document.querySelector(`tr[data-expense-id="${expense.id}"]`);
            if (!row) return;

            const date = new Date(expense.date);
            const formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            
            const safeDesc = (expense.description || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');

            row.innerHTML = `
                <td class="px-6 py-4 text-sm text-slate-900">${formattedDate}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-block px-3 py-1 rounded-full text-white text-xs font-semibold" style="background-color: ${expense.category_color};">
                        ${expense.category_name}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-slate-700">${expense.description || 'No description'}</td>
                <td class="px-6 py-4 text-sm font-semibold text-blue-600">₹${parseFloat(expense.amount).toFixed(2)}</td>
                <td class="px-6 py-4 text-sm">
                    ${expense.receipt_path ? 
                        `<button onclick="window.expenseManagerInstance.previewReceipt('/storage/${expense.receipt_path}', '${safeDesc}')" class="px-3 py-1 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200 text-sm font-semibold">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </button>` : 
                        '<span class="text-slate-400 text-sm">-</span>'
                    }
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex justify-center gap-2">
                        <button onclick="window.expenseManagerInstance.viewExpense(${expense.id})" class="p-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200" title="View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button onclick="window.expenseManagerInstance.editExpense(${expense.id})" class="p-2 border border-amber-300 text-amber-600 rounded-lg hover:bg-amber-50 transition-all duration-200" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="window.expenseManagerInstance.deleteExpense(${expense.id}, '${safeDesc}', '₹${parseFloat(expense.amount).toFixed(2)}', '${formattedDate}')" class="p-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            `;
        },

        showToast(message, type = 'info') {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'fixed top-4 right-4 z-[9999] space-y-2';
                document.body.appendChild(toastContainer);
            }

            const toastId = 'toast-' + Date.now();
            const bgClass = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

            const toastHTML = `
                <div id="${toastId}" class="${bgClass} text-white px-6 py-3 rounded-lg shadow-lg animate-slide-in-right">
                    ${message}
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);

            setTimeout(() => {
                const toastElement = document.getElementById(toastId);
                if (toastElement) {
                    toastElement.classList.add('animate-fade-out');
                    setTimeout(() => toastElement.remove(), 300);
                }
            }, 5000);
        },

        init() {
            window.expenseManagerInstance = this;
            this.updateStats();
        }
    }
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-in-right {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fade-out {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }

    .animate-slide-in-right {
        animation: slide-in-right 0.3s ease-out;
    }

    .animate-fade-out {
        animation: fade-out 0.3s ease-out;
    }
`;
document.head.appendChild(style);
</script>
@endsection