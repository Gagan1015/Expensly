@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Categories Management</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus"></i> Add New Category
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Categories</h6>
                                            <h4 class="mb-0" id="totalCategoriesCount">{{ $categories->count() }}</h4>
                                        </div>
                                        <i class="fas fa-tags fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Active Categories</h6>
                                            <h4 class="mb-0" id="activeCategoriesCount">{{ $categories->filter(fn($cat) => $cat->expenses_count > 0)->count() }}</h4>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Unused Categories</h6>
                                            <h4 class="mb-0" id="unusedCategoriesCount">{{ $categories->filter(fn($cat) => $cat->expenses_count == 0)->count() }}</h4>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Most Used</h6>
                                            <h4 class="mb-0">{{ $categories->sortByDesc('expenses_count')->first()->name ?? 'None' }}</h4>
                                        </div>
                                        <i class="fas fa-star fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Grid -->
                    <div class="row" id="categoriesGrid">
                        @forelse($categories as $category)
                            <div class="col-md-4 mb-3" data-category-id="{{ $category->id }}">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="d-flex align-items-center">
                                                <span class="badge rounded-pill me-2" style="background-color: {{ $category->color }}; width: 20px; height: 20px;">&nbsp;</span>
                                                <h6 class="card-title mb-0">{{ $category->name }}</h6>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm text-muted border-0" type="button" data-bs-toggle="dropdown" style="background: none; box-shadow: none;">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-action="view" data-category-id="{{ $category->id }}">
                                                            <i class="fas fa-eye me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-action="edit" data-category-id="{{ $category->id }}">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#" data-action="delete" data-category-id="{{ $category->id }}" data-name="{{ $category->name }}" data-expenses-count="{{ $category->expenses_count }}">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="card-text text-muted small">{{ $category->description ?: 'No description provided' }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-receipt me-1"></i>
                                                {{ $category->expenses_count }} {{ Str::plural('expense', $category->expenses_count) }}
                                            </small>
                                            <small class="text-muted">
                                                Created {{ $category->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No categories found</h5>
                                    <p class="text-muted">Start by creating your first category to organize your expenses.</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus"></i> Create First Category
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCategoryForm" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label">Color</label>
                        <div class="d-flex align-items-center">
                            <input type="color" class="form-control form-control-color me-3" id="color" name="color" value="#007bff" style="width: 60px;">
                            <small class="text-muted">Choose a color to identify this category</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_color" class="form-label">Color</label>
                        <div class="d-flex align-items-center">
                            <input type="color" class="form-control form-control-color me-3" id="edit_color" name="color" style="width: 60px;">
                            <small class="text-muted">Choose a color to identify this category</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Category Modal -->
<div class="modal fade" id="viewCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <div class="d-flex align-items-center">
                                <span id="view_color_badge" class="badge rounded-pill me-2" style="width: 20px; height: 20px;">&nbsp;</span>
                                <span id="view_name"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Expenses Count</label>
                            <div id="view_expenses_count"></div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <div id="view_description_text"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created</label>
                            <div id="view_created_at"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <div id="view_updated_at"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" data-action="edit-from-view">
                    <i class="fas fa-edit"></i> Edit Category
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete the category <strong id="delete_category_name"></strong>?</p>
                <div id="delete_category_expenses_warning" class="alert alert-danger d-none" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    This category is currently used by <strong id="delete_expenses_count"></strong> expense(s). Deleting this category will remove the category association from those expenses.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCategoryForm" style="display: inline;" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 11;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle category actions using event delegation
    document.addEventListener('click', function(e) {
        const target = e.target.closest('[data-action]');
        if (!target) return;
        
        const action = target.dataset.action;
        const categoryId = target.dataset.categoryId;
        
        if (action && categoryId) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close any open dropdown
            const dropdown = target.closest('.dropdown');
            if (dropdown) {
                const dropdownButton = dropdown.querySelector('[data-bs-toggle="dropdown"]');
                if (dropdownButton) {
                    bootstrap.Dropdown.getInstance(dropdownButton)?.hide();
                }
            }
            
            switch(action) {
                case 'view':
                    viewCategory(categoryId);
                    break;
                case 'edit':
                    editCategory(categoryId);
                    break;
                case 'delete':
                    const name = target.dataset.name || '';
                    const expensesCount = target.dataset.expensesCount || '0';
                    showDeleteModal(categoryId, name, expensesCount);
                    break;
                case 'edit-from-view':
                    editCategoryFromView();
                    break;
            }
        }
    });

    // Add category form submission
    document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
                addCategoryToGrid(data.category);
                updateStatistics();
                showToast(data.message, 'success');
                this.reset();
            } else {
                showToast(data.message || 'Error creating category', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message && typeof error.message === 'string') {
                showToast(error.message, 'error');
            } else if (error.errors) {
                // Handle validation errors
                const firstError = Object.values(error.errors)[0][0];
                showToast(firstError, 'error');
            } else {
                showToast('Error creating category', 'error');
            }
        });
    });

    // Edit category form submission
    document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editCategoryModal')).hide();
                updateCategoryInGrid(data.category);
                updateStatistics();
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Error updating category', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message && typeof error.message === 'string') {
                showToast(error.message, 'error');
            } else if (error.errors) {
                // Handle validation errors
                const firstError = Object.values(error.errors)[0][0];
                showToast(firstError, 'error');
            } else {
                showToast('Error updating category', 'error');
            }
        });
    });

    // Delete category form submission
    document.getElementById('deleteCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteCategoryModal')).hide();
                removeCategoryFromGrid(window.currentDeletingCategoryId);
                updateStatistics();
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Error deleting category', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message && typeof error.message === 'string') {
                showToast(error.message, 'error');
            } else if (error.errors) {
                // Handle validation errors
                const firstError = Object.values(error.errors)[0][0];
                showToast(firstError, 'error');
            } else {
                showToast('Error deleting category', 'error');
            }
        });
    });
});

// View category details
function viewCategory(categoryId) {
    console.log('Viewing category:', categoryId);
    
    if (!categoryId) {
        console.error('No category ID provided for viewing');
        showToast('Error: No category ID provided', 'error');
        return;
    }
    
    fetch(`/api/categories/${categoryId}`)
    .then(response => {
        console.log('View category response status:', response.status);
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        console.log('View category data received:', data);
        document.getElementById('view_name').textContent = data.name;
        document.getElementById('view_color_badge').style.backgroundColor = data.color;
        document.getElementById('view_expenses_count').textContent = `${data.expenses_count} ${data.expenses_count === 1 ? 'expense' : 'expenses'}`;
        document.getElementById('view_description_text').textContent = data.description || 'No description provided';
        document.getElementById('view_created_at').textContent = new Date(data.created_at).toLocaleDateString();
        document.getElementById('view_updated_at').textContent = new Date(data.updated_at).toLocaleDateString();
        
        window.currentViewingCategoryId = categoryId;
        console.log('Set currentViewingCategoryId to:', window.currentViewingCategoryId);
        new bootstrap.Modal(document.getElementById('viewCategoryModal')).show();
    })
    .catch(error => {
        console.error('Error loading category details:', error);
        if (error.message && typeof error.message === 'string') {
            showToast(error.message, 'error');
        } else {
            showToast('Error loading category details', 'error');
        }
    });
}

// Edit category
function editCategory(categoryId) {
    console.log('Attempting to edit category:', categoryId);
    
    if (!categoryId) {
        console.error('No category ID provided');
        showToast('Error: No category ID provided', 'error');
        return;
    }
    
    fetch(`/api/categories/${categoryId}`)
    .then(response => {
        console.log('Edit category response status:', response.status);
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        console.log('Edit category data received:', data);
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_description').value = data.description || '';
        document.getElementById('edit_color').value = data.color;
        document.getElementById('editCategoryForm').action = `/categories/${categoryId}`;
        
        window.currentEditingCategoryId = categoryId;
        new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
    })
    .catch(error => {
        console.error('Error loading category for edit:', error);
        if (error.message && typeof error.message === 'string') {
            showToast(error.message, 'error');
        } else {
            showToast('Error loading category details', 'error');
        }
    });
}

// Edit category from view modal
function editCategoryFromView() {
    if (!window.currentViewingCategoryId) {
        console.error('No category ID available for editing');
        showToast('Error: No category selected', 'error');
        return;
    }
    
    console.log('Editing category ID:', window.currentViewingCategoryId);
    bootstrap.Modal.getInstance(document.getElementById('viewCategoryModal')).hide();
    setTimeout(() => {
        editCategory(window.currentViewingCategoryId);
    }, 300);
}

// Show delete modal
function showDeleteModal(categoryId, name, expensesCount) {
    document.getElementById('delete_category_name').textContent = name;
    document.getElementById('deleteCategoryForm').action = `/categories/${categoryId}`;
    
    const warningDiv = document.getElementById('delete_category_expenses_warning');
    const expensesCountSpan = document.getElementById('delete_expenses_count');
    
    if (parseInt(expensesCount) > 0) {
        expensesCountSpan.textContent = expensesCount;
        warningDiv.classList.remove('d-none');
    } else {
        warningDiv.classList.add('d-none');
    }
    
    window.currentDeletingCategoryId = categoryId;
    new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
}

// Helper function to add new category to grid
function addCategoryToGrid(category) {
    const grid = document.getElementById('categoriesGrid');
    
    // Remove empty state if it exists
    const emptyState = grid.querySelector('.col-12');
    if (emptyState && emptyState.querySelector('.text-center')) {
        emptyState.remove();
    }
    
    const categoryCard = document.createElement('div');
    categoryCard.className = 'col-md-4 mb-3';
    categoryCard.setAttribute('data-category-id', category.id);
    categoryCard.innerHTML = `
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill me-2" style="background-color: ${category.color}; width: 20px; height: 20px;">&nbsp;</span>
                        <h6 class="card-title mb-0">${category.name}</h6>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" data-action="view" data-category-id="${category.id}">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-action="edit" data-category-id="${category.id}">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-action="delete" data-category-id="${category.id}" data-name="${category.name}" data-expenses-count="0">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <p class="card-text text-muted small">${category.description || 'No description provided'}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-receipt me-1"></i>
                        0 expenses
                    </small>
                    <small class="text-muted">
                        Just created
                    </small>
                </div>
            </div>
        </div>
    `;
    
    grid.insertBefore(categoryCard, grid.firstChild);
}

// Helper function to update existing category in grid
function updateCategoryInGrid(category) {
    const categoryCard = document.querySelector(`[data-category-id="${category.id}"]`);
    if (!categoryCard) return;
    
    const nameElement = categoryCard.querySelector('.card-title');
    const colorBadge = categoryCard.querySelector('.badge');
    const descriptionElement = categoryCard.querySelector('.card-text');
    const deleteLink = categoryCard.querySelector('[data-action="delete"]');
    
    nameElement.textContent = category.name;
    colorBadge.style.backgroundColor = category.color;
    descriptionElement.textContent = category.description || 'No description provided';
    deleteLink.setAttribute('data-name', category.name);
}

// Helper function to remove category from grid
function removeCategoryFromGrid(categoryId) {
    const categoryCard = document.querySelector(`[data-category-id="${categoryId}"]`);
    if (categoryCard) {
        categoryCard.remove();
        
        // Check if grid is empty and add empty state
        const grid = document.getElementById('categoriesGrid');
        if (grid.children.length === 0) {
            grid.innerHTML = `
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No categories found</h5>
                        <p class="text-muted">Start by creating your first category to organize your expenses.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus"></i> Create First Category
                        </button>
                    </div>
                </div>
            `;
        }
    }
}

// Update statistics (placeholder - would need server-side data in real implementation)
function updateStatistics() {
    // In a real implementation, you might want to fetch updated statistics from the server
    const totalCategories = document.querySelectorAll('[data-category-id]').length;
    
    // Update total categories count
    const totalCategoriesElement = document.getElementById('totalCategoriesCount');
    if (totalCategoriesElement) {
        totalCategoriesElement.textContent = totalCategories;
    }
    
    // For now, we'll just update the total count since we don't have expense data in frontend
    // In a real implementation, you would fetch these from the server
}

// Toast notification function
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast_' + Date.now();
    
    const bgClass = type === 'success' ? 'bg-success' : 
                   type === 'error' ? 'bg-danger' : 
                   type === 'warning' ? 'bg-warning' : 'bg-info';
    
    const toastHTML = `
        <div id="${toastId}" class="toast ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    const toastElement = new bootstrap.Toast(document.getElementById(toastId));
    toastElement.show();
    
    // Remove toast element after it's hidden
    document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}
</script>
@endsection