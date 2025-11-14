@extends('layouts.app')

@section('title', 'Create Category - Expense Manager')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-plus-circle me-2"></i>Create New Category
                </h2>
                <p class="text-muted mb-0">Add a new expense category</p>
            </div>
            <div>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Categories
                </a>
            </div>
        </div>

        <!-- Category Form -->
        <div class="card expense-form">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-tag me-2"></i>Category Details
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-tag me-1"></i>Category Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="e.g., Food & Dining"
                               maxlength="255"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label">
                            <i class="fas fa-palette me-1"></i>Color <span class="text-danger">*</span>
                        </label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="color" 
                                       class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" 
                                       name="color" 
                                       value="{{ old('color', '#667eea') }}" 
                                       title="Choose category color"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-sm color-preset" data-color="#ff6b6b" style="background-color: #ff6b6b; width: 30px; height: 30px;"></button>
                                    <button type="button" class="btn btn-sm color-preset" data-color="#4ecdc4" style="background-color: #4ecdc4; width: 30px; height: 30px;"></button>
                                    <button type="button" class="btn btn-sm color-preset" data-color="#45b7d1" style="background-color: #45b7d1; width: 30px; height: 30px;"></button>
                                    <button type="button" class="btn btn-sm color-preset" data-color="#f9ca24" style="background-color: #f9ca24; width: 30px; height: 30px;"></button>
                                    <button type="button" class="btn btn-sm color-preset" data-color="#6c5ce7" style="background-color: #6c5ce7; width: 30px; height: 30px;"></button>
                                    <button type="button" class="btn btn-sm color-preset" data-color="#a0e7e5" style="background-color: #a0e7e5; width: 30px; height: 30px;"></button>
                                    <button type="button" class="btn btn-sm color-preset" data-color="#95a5a6" style="background-color: #95a5a6; width: 30px; height: 30px;"></button>
                                </div>
                            </div>
                        </div>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose a color that represents this category</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-1"></i>Description (Optional)
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  maxlength="500"
                                  placeholder="Brief description of this category...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-custom">
                            <i class="fas fa-save me-1"></i>Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Preview
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div id="preview-color" class="rounded-circle" style="width: 40px; height: 40px; background-color: #667eea;"></div>
                    </div>
                    <div>
                        <h6 class="mb-0" id="preview-name">Category Name</h6>
                        <small class="text-muted" id="preview-description">Category description will appear here</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Common Categories -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Common Categories
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-category" 
                                        data-name="Food & Dining" data-color="#ff6b6b">
                                    🍽️ Food & Dining
                                </button>
                            </li>
                            <li class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-category" 
                                        data-name="Transportation" data-color="#4ecdc4">
                                    🚗 Transportation
                                </button>
                            </li>
                            <li class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-category" 
                                        data-name="Shopping" data-color="#f9ca24">
                                    🛍️ Shopping
                                </button>
                            </li>
                            <li class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-category" 
                                        data-name="Entertainment" data-color="#45b7d1">
                                    🎬 Entertainment
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-category" 
                                        data-name="Health & Medical" data-color="#6c5ce7">
                                    🏥 Health & Medical
                                </button>
                            </li>
                            <li class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-category" 
                                        data-name="Utilities" data-color="#a0e7e5">
                                    ⚡ Utilities
                                </button>
                            </li>
                            <li class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-category" 
                                        data-name="Travel" data-color="#e17055">
                                    ✈️ Travel
                                </button>
                            </li>
                            <li class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary quick-category" 
                                        data-name="Education" data-color="#00b894">
                                    🎓 Education
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-text">Click on any category above to quickly fill the form</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Update preview when form changes
    function updatePreview() {
        const name = document.getElementById('name').value || 'Category Name';
        const color = document.getElementById('color').value;
        const description = document.getElementById('description').value || 'Category description will appear here';
        
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-color').style.backgroundColor = color;
        document.getElementById('preview-description').textContent = description;
    }

    // Event listeners for real-time preview
    document.getElementById('name').addEventListener('input', updatePreview);
    document.getElementById('color').addEventListener('input', updatePreview);
    document.getElementById('description').addEventListener('input', updatePreview);

    // Color preset buttons
    document.querySelectorAll('.color-preset').forEach(button => {
        button.addEventListener('click', function() {
            const color = this.getAttribute('data-color');
            document.getElementById('color').value = color;
            updatePreview();
        });
    });

    // Quick category buttons
    document.querySelectorAll('.quick-category').forEach(button => {
        button.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const color = this.getAttribute('data-color');
            
            document.getElementById('name').value = name;
            document.getElementById('color').value = color;
            updatePreview();
        });
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const color = document.getElementById('color').value;
        
        if (!name) {
            e.preventDefault();
            alert('Please enter a category name.');
            return false;
        }
        
        if (!color) {
            e.preventDefault();
            alert('Please select a color.');
            return false;
        }
    });

    // Initialize preview
    updatePreview();
</script>
@endsection
