@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h5 class="text-xl font-semibold text-gray-800">Categories Management</h5>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition modal-trigger" data-modal="addCategoryModal">
                <i class="fas fa-plus mr-2"></i> Add New Category
            </button>
        </div>

        <div class="p-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-600 text-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h6 class="text-sm font-medium opacity-90">Total Categories</h6>
                            <h4 class="text-3xl font-bold mt-1" id="totalCategoriesCount">{{ $categories->count() }}</h4>
                        </div>
                        <i class="fas fa-tags text-4xl opacity-75"></i>
                    </div>
                </div>
                
                <div class="bg-green-600 text-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h6 class="text-sm font-medium opacity-90">Active Categories</h6>
                            <h4 class="text-3xl font-bold mt-1" id="activeCategoriesCount">{{ $categories->filter(fn($cat) => $cat->expenses_count > 0)->count() }}</h4>
                        </div>
                        <i class="fas fa-check-circle text-4xl opacity-75"></i>
                    </div>
                </div>
                
                <div class="bg-yellow-500 text-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h6 class="text-sm font-medium opacity-90">Unused Categories</h6>
                            <h4 class="text-3xl font-bold mt-1" id="unusedCategoriesCount">{{ $categories->filter(fn($cat) => $cat->expenses_count == 0)->count() }}</h4>
                        </div>
                        <i class="fas fa-exclamation-triangle text-4xl opacity-75"></i>
                    </div>
                </div>
                
                <div class="bg-cyan-600 text-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h6 class="text-sm font-medium opacity-90">Most Used</h6>
                            <h4 class="text-xl font-bold mt-1">{{ $categories->sortByDesc('expenses_count')->first()->name ?? 'None' }}</h4>
                        </div>
                        <i class="fas fa-star text-4xl opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="categoriesGrid">
                @forelse($categories as $category)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition" data-category-id="{{ $category->id }}">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center">
                                    <span class="inline-block w-5 h-5 rounded-full mr-2 category-color" data-color="{{ $category->color }}"></span>
                                    <h6 class="font-semibold text-gray-800">{{ $category->name }}</h6>
                                </div>
                                <div class="relative dropdown-container">
                                    <button class="text-gray-500 hover:text-gray-700 p-1 dropdown-toggle">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 category-action" data-action="view" data-category-id="{{ $category->id }}">
                                            <i class="fas fa-eye mr-2"></i>View Details
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 category-action" data-action="edit" data-category-id="{{ $category->id }}">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </a>
                                        <hr class="my-1 border-gray-200">
                                        <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 category-action" data-action="delete" data-category-id="{{ $category->id }}" data-name="{{ $category->name }}" data-expenses-count="{{ $category->expenses_count }}">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ $category->description ?: 'No description provided' }}</p>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>
                                    <i class="fas fa-receipt mr-1"></i>
                                    {{ $category->expenses_count }} {{ Str::plural('expense', $category->expenses_count) }}
                                </span>
                                <span>
                                    Created {{ $category->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <i class="fas fa-tags text-6xl text-gray-400 mb-4"></i>
                            <h5 class="text-xl font-semibold text-gray-600 mb-2">No categories found</h5>
                            <p class="text-gray-500 mb-4">Start by creating your first category to organize your expenses.</p>
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition modal-trigger" data-modal="addCategoryModal">
                                <i class="fas fa-plus mr-2"></i> Create First Category
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h5 class="text-xl font-semibold text-gray-800">Add New Category</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 modal-close" data-modal="addCategoryModal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="addCategoryForm" action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-600">*</span></label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" id="name" name="name" required>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none" id="description" name="description" rows="3"></textarea>
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <div class="flex items-center">
                        <input type="color" class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer mr-3" id="color" name="color" value="#007bff">
                        <small class="text-sm text-gray-500">Choose a color to identify this category</small>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition modal-close" data-modal="addCategoryModal">Cancel</button>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h5 class="text-xl font-semibold text-gray-800">Edit Category</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 modal-close" data-modal="editCategoryModal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-600">*</span></label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" id="edit_name" name="name" required>
                </div>
                <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none" id="edit_description" name="description" rows="3"></textarea>
                </div>
                <div>
                    <label for="edit_color" class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <div class="flex items-center">
                        <input type="color" class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer mr-3" id="edit_color" name="color">
                        <small class="text-sm text-gray-500">Choose a color to identify this category</small>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition modal-close" data-modal="editCategoryModal">Cancel</button>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Update Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Category Modal -->
<div id="viewCategoryModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h5 class="text-xl font-semibold text-gray-800">Category Details</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 modal-close" data-modal="viewCategoryModal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                    <div class="flex items-center">
                        <span id="view_color_badge" class="inline-block w-5 h-5 rounded-full mr-2"></span>
                        <span id="view_name" class="text-gray-800"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Expenses Count</label>
                    <div id="view_expenses_count" class="text-gray-800"></div>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <div id="view_description_text" class="text-gray-800"></div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Created</label>
                    <div id="view_created_at" class="text-gray-800"></div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Updated</label>
                    <div id="view_updated_at" class="text-gray-800"></div>
                </div>
            </div>
        </div>
        <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-3">
            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition modal-close" data-modal="viewCategoryModal">Close</button>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition edit-from-view-btn">
                <i class="fas fa-edit mr-2"></i> Edit Category
            </button>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div id="deleteCategoryModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h5 class="text-xl font-semibold text-red-600">Delete Category</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 modal-close" data-modal="deleteCategoryModal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Warning!</strong> This action cannot be undone.
            </div>
            <p class="text-gray-700">Are you sure you want to delete the category <strong id="delete_category_name"></strong>?</p>
            <div id="delete_category_expenses_warning" class="hidden bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mt-4">
                <i class="fas fa-exclamation-circle mr-2"></i>
                This category is currently used by <strong id="delete_expenses_count"></strong> expense(s). Deleting this category will remove the category association from those expenses.
            </div>
        </div>
        <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-3">
            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition modal-close" data-modal="deleteCategoryModal">Cancel</button>
            <form id="deleteCategoryForm" class="inline" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-2"></i> Delete Category
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Event delegation for dropdown toggles
document.addEventListener('click', function(event) {
    if (event.target.closest('.dropdown-toggle')) {
        event.stopPropagation();
        const button = event.target.closest('.dropdown-toggle');
        const dropdown = button.nextElementSibling;
        const allDropdowns = document.querySelectorAll('.dropdown-menu');
        
        allDropdowns.forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
        });
        
        dropdown.classList.toggle('hidden');
        return;
    }
    
    // Close dropdowns when clicking outside
    document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
});

// Event delegation for category actions
document.addEventListener('click', function(event) {
    if (event.target.closest('.category-action')) {
        event.preventDefault();
        event.stopPropagation();
        
        const actionElement = event.target.closest('.category-action');
        const action = actionElement.getAttribute('data-action');
        const categoryId = actionElement.getAttribute('data-category-id');
        const name = actionElement.getAttribute('data-name') || '';
        const expensesCount = actionElement.getAttribute('data-expenses-count') || 0;
        
        // Close dropdown
        const dropdown = actionElement.closest('.dropdown-menu');
        if (dropdown) dropdown.classList.add('hidden');
        
        switch(action) {
            case 'view':
                viewCategory(categoryId);
                break;
            case 'edit':
                editCategory(categoryId);
                break;
            case 'delete':
                showDeleteModal(categoryId, name, expensesCount);
                break;
        }
    }
});

// Event delegation for modal triggers
document.addEventListener('click', function(event) {
    if (event.target.closest('.modal-trigger')) {
        const modalId = event.target.closest('.modal-trigger').getAttribute('data-modal');
        openModal(modalId);
    }
});

// Event delegation for modal close buttons
document.addEventListener('click', function(event) {
    if (event.target.closest('.modal-close')) {
        const modalId = event.target.closest('.modal-close').getAttribute('data-modal');
        closeModal(modalId);
    }
});

// Event delegation for edit from view button
document.addEventListener('click', function(event) {
    if (event.target.closest('.edit-from-view-btn')) {
        editCategoryFromView();
    }
});

document.addEventListener('DOMContentLoaded', function() {
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
                closeModal('addCategoryModal');
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
                closeModal('editCategoryModal');
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
                closeModal('deleteCategoryModal');
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
    if (!categoryId) {
        showToast('Error: No category ID provided', 'error');
        return;
    }
    
    fetch(`/api/categories/${categoryId}`)
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('view_name').textContent = data.name;
        document.getElementById('view_color_badge').style.backgroundColor = data.color;
        document.getElementById('view_expenses_count').textContent = `${data.expenses_count} ${data.expenses_count === 1 ? 'expense' : 'expenses'}`;
        document.getElementById('view_description_text').textContent = data.description || 'No description provided';
        document.getElementById('view_created_at').textContent = new Date(data.created_at).toLocaleDateString();
        document.getElementById('view_updated_at').textContent = new Date(data.updated_at).toLocaleDateString();
        
        window.currentViewingCategoryId = categoryId;
        openModal('viewCategoryModal');
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
    if (!categoryId) {
        showToast('Error: No category ID provided', 'error');
        return;
    }
    
    fetch(`/api/categories/${categoryId}`)
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_description').value = data.description || '';
        document.getElementById('edit_color').value = data.color;
        document.getElementById('editCategoryForm').action = `/categories/${categoryId}`;
        
        window.currentEditingCategoryId = categoryId;
        openModal('editCategoryModal');
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
        showToast('Error: No category selected', 'error');
        return;
    }
    
    closeModal('viewCategoryModal');
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
        warningDiv.classList.remove('hidden');
    } else {
        warningDiv.classList.add('hidden');
    }
    
    window.currentDeletingCategoryId = categoryId;
    openModal('deleteCategoryModal');
}

// Helper function to add new category to grid
function addCategoryToGrid(category) {
    const grid = document.getElementById('categoriesGrid');
    
    // Remove empty state if it exists
    const emptyState = grid.querySelector('.col-span-full');
    if (emptyState) {
        emptyState.remove();
    }
    
    const categoryCard = document.createElement('div');
    categoryCard.className = 'bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition';
    categoryCard.setAttribute('data-category-id', category.id);
    categoryCard.innerHTML = `
        <div class="p-4">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center">
                    <span class="inline-block w-5 h-5 rounded-full mr-2 category-color" data-color="${category.color}"></span>
                    <h6 class="font-semibold text-gray-800">${category.name}</h6>
                </div>
                <div class="relative dropdown-container">
                    <button class="text-gray-500 hover:text-gray-700 p-1 dropdown-toggle">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 category-action" data-action="view" data-category-id="${category.id}">
                            <i class="fas fa-eye mr-2"></i>View Details
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 category-action" data-action="edit" data-category-id="${category.id}">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <hr class="my-1 border-gray-200">
                        <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 category-action" data-action="delete" data-category-id="${category.id}" data-name="${category.name}" data-expenses-count="${category.expenses_count || 0}">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </a>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-3">${category.description || 'No description provided'}</p>
            <div class="flex justify-between items-center text-xs text-gray-500">
                <span>
                    <i class="fas fa-receipt mr-1"></i>
                    ${category.expenses_count || 0} ${(category.expenses_count || 0) === 1 ? 'expense' : 'expenses'}
                </span>
                <span>
                    Created ${new Date(category.created_at).toLocaleDateString()}
                </span>
            </div>
        </div>
    `;
    
    grid.appendChild(categoryCard);
    
    // Initialize the color for the newly added category
    const colorElement = categoryCard.querySelector('.category-color');
    if (colorElement && category.color) {
        colorElement.style.backgroundColor = category.color;
    }
}

// Helper function to update category in grid
function updateCategoryInGrid(category) {
    const categoryCard = document.querySelector(`[data-category-id="${category.id}"]`);
    if (categoryCard) {
        const colorSpan = categoryCard.querySelector('.category-color');
        const nameElement = categoryCard.querySelector('h6.font-semibold');
        const descriptionElement = categoryCard.querySelector('p.text-sm.text-gray-600');
        const expensesCountElement = categoryCard.querySelector('.fas.fa-receipt').parentElement;
        
        if (colorSpan) {
            colorSpan.setAttribute('data-color', category.color);
            colorSpan.style.backgroundColor = category.color;
        }
        if (nameElement) nameElement.textContent = category.name;
        if (descriptionElement) descriptionElement.textContent = category.description || 'No description provided';
        if (expensesCountElement) {
            expensesCountElement.innerHTML = `
                <i class="fas fa-receipt mr-1"></i>
                ${category.expenses_count || 0} ${(category.expenses_count || 0) === 1 ? 'expense' : 'expenses'}
            `;
        }
        
        // Update dropdown actions with new data
        const deleteAction = categoryCard.querySelector('[data-action="delete"]');
        if (deleteAction) {
            deleteAction.setAttribute('data-name', category.name);
            deleteAction.setAttribute('data-expenses-count', category.expenses_count || 0);
        }
    }
}

// Helper function to remove category from grid
function removeCategoryFromGrid(categoryId) {
    const categoryCard = document.querySelector(`[data-category-id="${categoryId}"]`);
    if (categoryCard) {
        categoryCard.remove();
        
        // Check if grid is empty and show empty state
        const grid = document.getElementById('categoriesGrid');
        const remainingCards = grid.querySelectorAll('[data-category-id]');
        
        if (remainingCards.length === 0) {
            const emptyState = document.createElement('div');
            emptyState.className = 'col-span-full';
            emptyState.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-tags text-6xl text-gray-400 mb-4"></i>
                    <h5 class="text-xl font-semibold text-gray-600 mb-2">No categories found</h5>
                    <p class="text-gray-500 mb-4">Start by creating your first category to organize your expenses.</p>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition modal-trigger" data-modal="addCategoryModal">
                        <i class="fas fa-plus mr-2"></i> Create First Category
                    </button>
                </div>
            `;
            grid.appendChild(emptyState);
        }
    }
}

// Helper function to update statistics
function updateStatistics() {
    // This would typically fetch updated statistics from the server
    // For now, we'll update based on current DOM state
    const categoryCards = document.querySelectorAll('[data-category-id]');
    const totalCount = categoryCards.length;
    
    let activeCount = 0;
    let unusedCount = 0;
    let mostUsedCategory = 'None';
    let maxExpenses = 0;
    
    categoryCards.forEach(card => {
        const expensesText = card.querySelector('.fas.fa-receipt').parentElement.textContent;
        const expensesCount = parseInt(expensesText.match(/\d+/)[0]) || 0;
        
        if (expensesCount > 0) {
            activeCount++;
            if (expensesCount > maxExpenses) {
                maxExpenses = expensesCount;
                mostUsedCategory = card.querySelector('h6.font-semibold').textContent;
            }
        } else {
            unusedCount++;
        }
    });
    
    // Update statistics cards
    document.getElementById('totalCategoriesCount').textContent = totalCount;
    document.getElementById('activeCategoriesCount').textContent = activeCount;
    document.getElementById('unusedCategoriesCount').textContent = unusedCount;
    
    const mostUsedElement = document.querySelector('.bg-cyan-600 h4');
    if (mostUsedElement) {
        mostUsedElement.textContent = mostUsedCategory;
    }
}

// Toast notification system
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : 
                   type === 'error' ? 'bg-red-500' : 
                   type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
    
    const icon = type === 'success' ? 'fas fa-check-circle' : 
                type === 'error' ? 'fas fa-exclamation-circle' : 
                type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
    
    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300`;
    toast.innerHTML = `
        <i class="${icon}"></i>
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, 5000);
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        const modalId = event.target.id;
        closeModal(modalId);
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal:not(.hidden)');
        openModals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Initialize category colors on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeCategoryColors();
});

// Function to set category colors from data attributes
function initializeCategoryColors() {
    const categoryColorElements = document.querySelectorAll('.category-color');
    categoryColorElements.forEach(element => {
        const color = element.getAttribute('data-color');
        if (color) {
            element.style.backgroundColor = color;
        }
    });
}
</script>

<style>
.category-color {
    background-color: #6b7280; /* Default gray color as fallback */
}
</style>
@endsection