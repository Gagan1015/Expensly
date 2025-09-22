@extends('layouts.app')

@section('title', 'My Expenses - Expense Manager')

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-receipt me-2"></i>My Expenses
                </h2>
                <p class="text-muted mb-0">Manage and track all your expenses</p>
            </div>
            <div>
                <button class="btn btn-custom btn-lg" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                    <i class="fas fa-plus me-2"></i>Add New Expense
                </button>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="dateFilter" class="form-label">Date Range</label>
                        <select class="form-select" id="dateFilter">
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month" selected>This Month</option>
                            <option value="quarter">This Quarter</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="categoryFilter" class="form-label">Category</label>
                        <select class="form-select" id="categoryFilter">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="searchInput" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search descriptions...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-primary" data-action="clear-filters">
                                <i class="fas fa-times me-1"></i>Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Custom Date Range -->
                <div class="row" id="customDateRange" style="display: none;">
                    <div class="col-md-3 mb-3">
                        <label for="dateFrom" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="dateFrom">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="dateTo" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="dateTo">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stats-card text-center h-100">
            <div class="card-body">
                <i class="fas fa-calculator fa-2x text-primary mb-3"></i>
                <h5 class="card-title">Total</h5>
                <p class="card-text expense-amount text-primary">₹{{ number_format($totalAmount, 0) }}</p>
                <small class="text-muted">{{ $expenses->count() }} expenses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stats-card text-center h-100">
            <div class="card-body">
                <i class="fas fa-chart-line fa-2x text-success mb-3"></i>
                <h5 class="card-title">Average</h5>
                <p class="card-text expense-amount text-info">₹{{ number_format($expenses->count() > 0 ? $totalAmount / $expenses->count() : 0, 0) }}</p>
                <small class="text-muted">Per expense</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stats-card text-center h-100">
            <div class="card-body">
                <i class="fas fa-tags fa-2x text-warning mb-3"></i>
                <h5 class="card-title">Categories</h5>
                <p class="card-text expense-amount text-warning">{{ $categories->count() }}</p>
                <small class="text-muted">Available</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stats-card text-center h-100">
            <div class="card-body">
                <i class="fas fa-calendar fa-2x text-danger mb-3"></i>
                <h5 class="card-title">This Month</h5>
                @php
                    $thisMonthExpenses = $expenses->filter(function($expense) {
                        return $expense->date->format('Y-m') === now()->format('Y-m');
                    });
                    $thisMonthTotal = $thisMonthExpenses->sum('amount');
                @endphp
                <p class="card-text expense-amount text-danger">₹{{ number_format($thisMonthTotal, 0) }}</p>
                <small class="text-muted">{{ $thisMonthExpenses->count() }} expenses</small>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>Expense List
                </h5>
                <div class="btn-group" role="group">
                    <a href="{{ route('expenses.export.csv') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-csv me-1"></i>Export CSV
                    </a>
                    <a href="{{ route('expenses.reports') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-chart-bar me-1"></i>Reports
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="expensesTable">
                        <thead class="expense-table">
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Receipt</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr data-expense-id="{{ $expense->id }}">
                                <td>{{ $expense->date->format('M d, Y') }}</td>
                                <td>
                                    @if($expense->category)
                                        <span class="badge" style="background-color: {{ $expense->category->color }}; color: white;">
                                            {{ $expense->category->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">No Category</span>
                                    @endif
                                </td>
                                <td>{{ $expense->description ?: 'No description' }}</td>
                                <td class="expense-amount">₹{{ number_format($expense->amount, 2) }}</td>
                                <td>
                                    @if($expense->receipt_path)
                                        <button class="btn btn-sm btn-outline-info" data-action="preview-receipt" data-receipt-url="{{ Storage::url($expense->receipt_path) }}" data-description="{{ $expense->description }}">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-info" data-action="view" data-expense-id="{{ $expense->id }}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-primary" data-action="edit" data-expense-id="{{ $expense->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" data-action="delete" data-expense-id="{{ $expense->id }}" data-description="{{ $expense->description }}" data-amount="₹{{ number_format($expense->amount, 2) }}" data-date="{{ $expense->date->format('M d, Y') }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3"></i>
                                        <h5>No expenses found</h5>
                                        <p>Start by adding your first expense!</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addExpenseModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Add New Expense
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addExpenseForm" action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="add_amount" name="amount" step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="add_date" name="date" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_receipt" class="form-label">Receipt (Optional)</label>
                            <input type="file" class="form-control" id="add_receipt" name="receipt" accept="image/*">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add_description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="add_description" name="description" rows="3" placeholder="Enter expense description..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Expense Modal -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editExpenseModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Expense
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExpenseForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_amount" name="amount" step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_date" name="date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_receipt" class="form-label">Receipt (Optional)</label>
                            <input type="file" class="form-control" id="edit_receipt" name="receipt" accept="image/*">
                            <div id="current_receipt"></div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit_description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Enter expense description..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Update Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Expense Modal -->
<div class="modal fade" id="viewExpenseModal" tabindex="-1" aria-labelledby="viewExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewExpenseModalLabel">
                    <i class="fas fa-eye me-2"></i>Expense Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Amount:</strong>
                        <p id="view_amount" class="text-primary fs-4"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Date:</strong>
                        <p id="view_date"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Category:</strong>
                        <p id="view_category"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Receipt:</strong>
                        <div id="view_receipt"></div>
                    </div>
                    <div class="col-12">
                        <strong>Description:</strong>
                        <p id="view_description" class="mt-2"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" data-action="edit-from-view">
                    <i class="fas fa-edit me-1"></i>Edit Expense
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteExpenseModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5>Are you sure you want to delete this expense?</h5>
                    <p class="text-muted">This action cannot be undone.</p>
                    <div class="expense-details mt-3 p-3 bg-light rounded">
                        <strong>Amount: </strong><span id="delete_amount"></span><br>
                        <strong>Description: </strong><span id="delete_description"></span><br>
                        <strong>Date: </strong><span id="delete_date"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Delete Expense
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Preview Modal -->
<div class="modal fade" id="receiptPreviewModal" tabindex="-1" aria-labelledby="receiptPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="receiptPreviewModalLabel">
                    <i class="fas fa-image me-2"></i>Receipt Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="receiptContent">
                    <img id="receiptImage" src="" alt="Receipt" class="img-fluid rounded shadow-sm" style="max-height: 500px; max-width: 100%;">
                    <p id="receiptDescription" class="mt-3 text-muted"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="downloadReceiptBtn" href="" download class="btn btn-primary">
                    <i class="fas fa-download me-1"></i>Download
                </a>
                <a id="openReceiptBtn" href="" target="_blank" class="btn btn-info">
                    <i class="fas fa-external-link-alt me-1"></i>Open in New Tab
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle expense actions using event delegation
    document.addEventListener('click', function(e) {
        const target = e.target.closest('[data-action]');
        if (!target) return;
        
        const action = target.dataset.action;
        const expenseId = target.dataset.expenseId;
        
        if (action && expenseId) {
            e.preventDefault();
            
            switch(action) {
                case 'view':
                    viewExpense(expenseId);
                    break;
                case 'edit':
                    editExpense(expenseId);
                    break;
                case 'delete':
                    const description = target.dataset.description || '';
                    const amount = target.dataset.amount || '';
                    const date = target.dataset.date || '';
                    showDeleteModal(expenseId, description, amount, date);
                    break;
                case 'preview-receipt':
                    const receiptUrl = target.dataset.receiptUrl;
                    const receiptDescription = target.dataset.description || 'Receipt';
                    console.log('Receipt preview requested:', receiptUrl);
                    if (receiptUrl && receiptUrl.trim() !== '') {
                        previewReceipt(receiptUrl, receiptDescription);
                    } else {
                        showToast('No receipt image available', 'error');
                    }
                    break;
                case 'clear-filters':
                    clearFilters();
                    break;
                case 'edit-from-view':
                    editExpenseFromView();
                    break;
            }
        }
    });

    // Filter functionality
    const dateFilter = document.getElementById('dateFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const searchInput = document.getElementById('searchInput');
    const customDateRange = document.getElementById('customDateRange');
    
    dateFilter.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
        }
        filterExpenses();
    });
    
    categoryFilter.addEventListener('change', filterExpenses);
    searchInput.addEventListener('input', filterExpenses);
    
    // Add event listeners for custom date range inputs
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    
    if (dateFrom) {
        dateFrom.addEventListener('change', function() {
            if (dateFilter.value === 'custom') {
                filterExpenses();
            }
        });
    }
    
    if (dateTo) {
        dateTo.addEventListener('change', function() {
            if (dateFilter.value === 'custom') {
                filterExpenses();
            }
        });
    }
    
    function filterExpenses() {
        // This would typically make an AJAX call to filter expenses
        // For now, we'll implement client-side filtering
        const table = document.getElementById('expensesTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            let show = true;
            
            // Date filter
            if (dateFilter.value !== 'all') {
                const dateCell = row.cells[0]; // Assuming date is in first column
                if (dateCell) {
                    const expenseDate = new Date(dateCell.textContent.trim());
                    const now = new Date();
                    
                    if (!isNaN(expenseDate.getTime())) {
                        let showByDate = false;
                        
                        switch (dateFilter.value) {
                            case 'today':
                                showByDate = isSameDay(expenseDate, now);
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
                                const fromDate = document.getElementById('dateFrom').value;
                                const toDate = document.getElementById('dateTo').value;
                                if (fromDate && toDate) {
                                    const from = new Date(fromDate);
                                    const to = new Date(toDate);
                                    to.setHours(23, 59, 59, 999); // Include the entire end date
                                    showByDate = expenseDate >= from && expenseDate <= to;
                                } else {
                                    showByDate = true; // Show all if custom range not properly set
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
            if (show && categoryFilter.value !== 'all') {
                const categoryCell = row.cells[1];
                if (categoryCell && !categoryCell.textContent.toLowerCase().includes(categoryFilter.options[categoryFilter.selectedIndex].text.toLowerCase())) {
                    show = false;
                }
            }
            
            // Search filter
            if (show && searchInput.value) {
                const descriptionCell = row.cells[2];
                if (descriptionCell && !descriptionCell.textContent.toLowerCase().includes(searchInput.value.toLowerCase())) {
                    show = false;
                }
            }
            
            row.style.display = show ? '' : 'none';
        }
        
        // Update expense count after filtering
        updateFilteredExpenseCount();
    }
    
    // Helper function to check if two dates are the same day
    function isSameDay(date1, date2) {
        return date1.getDate() === date2.getDate() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getFullYear() === date2.getFullYear();
    }
    
    // Helper function to update expense count after filtering
    function updateFilteredExpenseCount() {
        const table = document.getElementById('expensesTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        let visibleCount = 0;
        let totalAmount = 0;
        
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].style.display !== 'none') {
                visibleCount++;
                // Try to get amount from the row (assuming it's in a specific cell)
                const amountCell = rows[i].cells[3]; // Adjust index based on your table structure
                if (amountCell) {
                    const amountText = amountCell.textContent.replace(/[₹,]/g, '');
                    const amount = parseFloat(amountText);
                    if (!isNaN(amount)) {
                        totalAmount += amount;
                    }
                }
            }
        }
        
        // Update the summary cards if they exist
        const totalCard = document.querySelector('.stats-card .expense-amount.text-primary');
        const countElement = document.querySelector('.stats-card small');
        
        if (totalCard) {
            totalCard.textContent = `₹${totalAmount.toLocaleString()}`;
        }
        if (countElement) {
            countElement.textContent = `${visibleCount} expenses`;
        }
    }

    // Handle form submissions
    document.getElementById('addExpenseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Expense added successfully!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('addExpenseModal')).hide();
                // Add new row to table instead of reloading
                addExpenseToTable(data.expense);
                // Reset form
                this.reset();
            } else {
                showToast(data.message || 'Error adding expense', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error adding expense. Please try again.', 'error');
        });
    });

    document.getElementById('editExpenseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        // Add the _method field for Laravel to recognize this as a PUT request
        formData.append('_method', 'PUT');
        
        fetch(this.action, {
            method: 'POST', // Laravel expects POST with _method=PUT for form submissions
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Expense updated successfully!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('editExpenseModal')).hide();
                // Update existing row in table instead of reloading
                updateExpenseInTable(data.expense);
            } else {
                showToast(data.message || 'Error updating expense', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating expense. Please try again.', 'error');
        });
    });

    // Handle delete confirmation
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        const expenseId = window.expenseToDelete;
        
        fetch(`/expenses/${expenseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Expense deleted successfully!', 'success');
                // Remove row from table
                document.querySelector(`tr[data-expense-id="${expenseId}"]`).remove();
                // Hide modal
                bootstrap.Modal.getInstance(document.getElementById('deleteExpenseModal')).hide();
            } else {
                showToast('Error deleting expense', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting expense. Please try again.', 'error');
        });
    });
});

function clearFilters() {
    document.getElementById('dateFilter').value = 'month';
    document.getElementById('categoryFilter').value = 'all';
    document.getElementById('searchInput').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    document.getElementById('customDateRange').style.display = 'none';
    filterExpenses();
}

function editExpense(expenseId) {
    // Fetch expense data and populate edit modal
    fetch(`/api/expenses/${expenseId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('edit_category_id').value = data.category_id;
        document.getElementById('edit_amount').value = data.amount;
        document.getElementById('edit_date').value = data.date;
        document.getElementById('edit_description').value = data.description || '';
        
        // Update form action
        document.getElementById('editExpenseForm').action = `/expenses/${expenseId}`;
        
        // Show current receipt if exists
        const currentReceiptDiv = document.getElementById('current_receipt');
        if (data.receipt_path) {
            currentReceiptDiv.innerHTML = `
                <small class="text-muted">Current receipt: 
                    <a href="/storage/${data.receipt_path}" target="_blank">View</a>
                </small>
            `;
        } else {
            currentReceiptDiv.innerHTML = '';
        }
        
        // Show modal
        new bootstrap.Modal(document.getElementById('editExpenseModal')).show();
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error loading expense data', 'error');
    });
}

// View expense function
function viewExpense(expenseId) {
    fetch(`/api/expenses/${expenseId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('view_amount').textContent = `₹${parseFloat(data.amount).toFixed(2)}`;
        document.getElementById('view_date').textContent = new Date(data.date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('view_description').textContent = data.description || 'No description provided';
        
        // Category
        if (data.category) {
            document.getElementById('view_category').innerHTML = `
                <span class="badge" style="background-color: ${data.category.color}; color: white;">
                    ${data.category.name}
                </span>
            `;
        } else {
            document.getElementById('view_category').innerHTML = '<span class="badge bg-secondary">No Category</span>';
        }
        
        // Receipt
        const receiptDiv = document.getElementById('view_receipt');
        if (data.receipt_path) {
            const description = (data.description || 'Receipt').replace(/'/g, "\\'");
            receiptDiv.innerHTML = `
                <button class="btn btn-sm btn-outline-info" data-action="preview-receipt" data-receipt-url="/storage/${data.receipt_path}" data-description="${description}">
                    <i class="fas fa-image me-1"></i>View Receipt
                </button>
            `;
        } else {
            receiptDiv.innerHTML = '<span class="text-muted">No receipt uploaded</span>';
        }
        
        // Store expense ID for potential edit action
        window.currentViewingExpenseId = expenseId;
        
        // Show modal
        new bootstrap.Modal(document.getElementById('viewExpenseModal')).show();
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error loading expense details', 'error');
    });
}

// Edit expense from view modal
function editExpenseFromView() {
    bootstrap.Modal.getInstance(document.getElementById('viewExpenseModal')).hide();
    setTimeout(() => {
        editExpense(window.currentViewingExpenseId);
    }, 300);
}

// Show delete confirmation modal
function showDeleteModal(expenseId, description, amount, date) {
    document.getElementById('delete_amount').textContent = amount;
    document.getElementById('delete_description').textContent = description || 'No description';
    document.getElementById('delete_date').textContent = date;
    
    // Store expense ID for deletion
    window.expenseToDelete = expenseId;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('deleteExpenseModal')).show();
}

// Receipt preview function
function previewReceipt(receiptUrl, description) {
    if (!receiptUrl) {
        showToast('No receipt image available', 'error');
        return;
    }
    
    console.log('Previewing receipt:', receiptUrl);
    
    const receiptImage = document.getElementById('receiptImage');
    const receiptDescription = document.getElementById('receiptDescription');
    const downloadBtn = document.getElementById('downloadReceiptBtn');
    const openBtn = document.getElementById('openReceiptBtn');
    
    // Show loading state
    receiptImage.src = '';
    receiptDescription.textContent = 'Loading...';
    
    // Set up image load handlers
    receiptImage.onload = function() {
        console.log('Receipt image loaded successfully');
        receiptDescription.textContent = description || 'Receipt';
    };
    
    receiptImage.onerror = function() {
        console.error('Failed to load receipt image:', receiptUrl);
        receiptImage.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjhmOWZhIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZjNzU3ZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBmb3VuZDwvdGV4dD48L3N2Zz4=';
        receiptDescription.textContent = 'Receipt image could not be loaded';
        showToast('Failed to load receipt image', 'error');
    };
    
    // Set image source and other attributes
    receiptImage.src = receiptUrl;
    downloadBtn.href = receiptUrl;
    openBtn.href = receiptUrl;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('receiptPreviewModal')).show();
}

// Helper function to add new expense to table
function addExpenseToTable(expense) {
    const tbody = document.querySelector('table tbody');
    
    // Remove "no expenses found" message if it exists
    const noExpensesRow = tbody.querySelector('tr td[colspan="6"]');
    if (noExpensesRow) {
        noExpensesRow.closest('tr').remove();
    }
    
    // Format date
    const date = new Date(expense.date);
    const formattedDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
    
    // Safely escape description for JavaScript
    const safeDescription = (expense.description || 'Receipt').replace(/'/g, "\\'").replace(/"/g, '\\"');
    const safeDeleteDescription = (expense.description || '').replace(/'/g, "\\'").replace(/"/g, '\\"');
    
    // Create new row
    const newRow = document.createElement('tr');
    newRow.setAttribute('data-expense-id', expense.id);
    newRow.innerHTML = `
        <td>${formattedDate}</td>
        <td>
            <span class="badge" style="background-color: ${expense.category_color}; color: white;">
                ${expense.category_name}
            </span>
        </td>
        <td>${expense.description || 'No description'}</td>
        <td class="expense-amount">₹${parseFloat(expense.amount).toFixed(2)}</td>
        <td>
            ${expense.receipt_path ? 
                `<button class="btn btn-sm btn-outline-info" data-action="preview-receipt" data-receipt-url="/storage/${expense.receipt_path}" data-description="${safeDescription}">
                    <i class="fas fa-image"></i>
                </button>` : 
                '<span class="text-muted">-</span>'
            }
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-outline-info" data-action="view" data-expense-id="${expense.id}" title="View">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-outline-primary" data-action="edit" data-expense-id="${expense.id}" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger" data-action="delete" data-expense-id="${expense.id}" data-description="${safeDeleteDescription}" data-amount="₹${parseFloat(expense.amount).toFixed(2)}" data-date="${formattedDate}" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    `;
    
    // Insert at the beginning of tbody (most recent first)
    tbody.insertBefore(newRow, tbody.firstChild);
}

function updateExpenseInTable(expense) {
    const row = document.querySelector(`tr[data-expense-id="${expense.id}"]`);
    if (!row) return;
    
    // Format date
    const date = new Date(expense.date);
    const formattedDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
    
    // Safely escape description for JavaScript
    const safeDescription = (expense.description || 'Receipt').replace(/'/g, "\\'").replace(/"/g, '\\"');
    const safeDeleteDescription = (expense.description || '').replace(/'/g, "\\'").replace(/"/g, '\\"');
    
    // Update row content
    row.innerHTML = `
        <td>${formattedDate}</td>
        <td>
            <span class="badge" style="background-color: ${expense.category_color}; color: white;">
                ${expense.category_name}
            </span>
        </td>
        <td>${expense.description || 'No description'}</td>
        <td class="expense-amount">₹${parseFloat(expense.amount).toFixed(2)}</td>
        <td>
            ${expense.receipt_path ? 
                `<button class="btn btn-sm btn-outline-info" data-action="preview-receipt" data-receipt-url="/storage/${expense.receipt_path}" data-description="${safeDescription}">
                    <i class="fas fa-image"></i>
                </button>` : 
                '<span class="text-muted">-</span>'
            }
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-outline-info" data-action="view" data-expense-id="${expense.id}" title="View">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-outline-primary" data-action="edit" data-expense-id="${expense.id}" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger" data-action="delete" data-expense-id="${expense.id}" data-description="${safeDeleteDescription}" data-amount="₹${parseFloat(expense.amount).toFixed(2)}" data-date="${formattedDate}" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    `;
}

// Toast notification function
function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast
    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
    
    const toastHTML = `
        <div id="${toastId}" class="toast ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    // Show toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    
    toast.show();
    
    // Remove toast from DOM after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}
</script>
@endsection