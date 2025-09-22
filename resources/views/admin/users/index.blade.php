@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Users Management</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="fas fa-user-plus me-2"></i>Add New User
    </button>
</div>

<!-- Search and Filter -->
<div class="card admin-card mb-4">
    <div class="card-body">
        <form id="usersFilterForm" method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Users</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by name or email...">
                </div>
            </div>
            <div class="col-md-3">
                <label for="role" class="form-label">Filter by Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort" class="form-label">Sort By</label>
                <select class="form-select" id="sort" name="sort">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Joined</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card admin-card" id="usersCard">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Users ({{ $users->total() }})</h5>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" onclick="selectAll()">
                <i class="fas fa-check-square me-1"></i>Select All
            </button>
            <button class="btn btn-outline-danger" onclick="bulkDelete()">
                <i class="fas fa-trash me-1"></i>Delete Selected
            </button>
        </div>
    </div>
    @include('admin.users.partials.table', ['users' => $users])
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Create New User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_name" class="form-label">
                                <i class="fas fa-user me-1"></i>Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="create_name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="create_email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="create_email" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>Phone Number
                            </label>
                            <input type="tel" class="form-control" id="create_phone" name="phone">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="create_role" class="form-label">
                                <i class="fas fa-shield-alt me-1"></i>Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="create_role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="create_password" name="password" required minlength="8">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="create_password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1"></i>Confirm Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="create_password_confirmation" name="password_confirmation" required minlength="8">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="create_bio" class="form-label">
                            <i class="fas fa-info-circle me-1"></i>Bio
                        </label>
                        <textarea class="form-control" id="create_bio" name="bio" rows="3" maxlength="500"></textarea>
                        <div class="form-text">Maximum 500 characters</div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="create_profile_picture" class="form-label">
                            <i class="fas fa-camera me-1"></i>Profile Picture
                        </label>
                        <input type="file" class="form-control" id="create_profile_picture" name="profile_picture" accept="image/*">
                        <div class="form-text">Accepted formats: JPG, PNG, GIF. Max size: 2MB</div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="fas fa-user-edit me-2"></i>Edit User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="edit_current_profile_picture" src="" alt="Profile" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label">
                                <i class="fas fa-user me-1"></i>Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>Phone Number
                            </label>
                            <input type="tel" class="form-control" id="edit_phone" name="phone">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_role" class="form-label">
                                <i class="fas fa-shield-alt me-1"></i>Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label">
                                <i class="fas fa-lock me-1"></i>New Password
                            </label>
                            <input type="password" class="form-control" id="edit_password" name="password" minlength="8">
                            <div class="form-text">Leave blank to keep current password</div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1"></i>Confirm New Password
                            </label>
                            <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" minlength="8">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_bio" class="form-label">
                            <i class="fas fa-info-circle me-1"></i>Bio
                        </label>
                        <textarea class="form-control" id="edit_bio" name="bio" rows="3" maxlength="500"></textarea>
                        <div class="form-text">Maximum 500 characters</div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_profile_picture" class="form-label">
                            <i class="fas fa-camera me-1"></i>New Profile Picture
                        </label>
                        <input type="file" class="form-control" id="edit_profile_picture" name="profile_picture" accept="image/*">
                        <div class="form-text">Leave blank to keep current picture. Accepted formats: JPG, PNG, GIF. Max size: 2MB</div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewUserModalLabel">
                    <i class="fas fa-user me-2"></i>User Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="view_profile_picture" src="" alt="Profile" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <h4 id="view_name" class="mb-1"></h4>
                    <span id="view_role_badge" class="badge fs-6"></span>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-envelope me-2 text-primary"></i>Email</h6>
                                <p id="view_email" class="card-text mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-phone me-2 text-primary"></i>Phone</h6>
                                <p id="view_phone" class="card-text mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-info-circle me-2 text-primary"></i>Bio</h6>
                        <p id="view_bio" class="card-text mb-0"></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-calendar-plus me-2 text-primary"></i>Joined</h6>
                                <p id="view_created_at" class="card-text mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-sync-alt me-2 text-primary"></i>Last Updated</h6>
                                <p id="view_updated_at" class="card-text mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-warning" onclick="editUserFromView()">
                    <i class="fas fa-edit me-1"></i>Edit User
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the user <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>This action cannot be undone!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // AJAX filter/search/sort
    (function(){
        const form = document.getElementById('usersFilterForm');
        const usersCard = document.getElementById('usersCard');
        let debounceTimer;

        function buildQueryString(formEl){
            const params = new URLSearchParams(new FormData(formEl));
            return params.toString();
        }

        async function loadUsers(){
            const qs = buildQueryString(form);
            const url = `{{ route('admin.users.index') }}?${qs}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            usersCard.innerHTML = data.html;
        }

        function debouncedLoad(){
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(loadUsers, 300);
        }

        // Submit button
        form.addEventListener('submit', function(e){
            e.preventDefault();
            loadUsers();
        });

        // Live on change
        form.querySelectorAll('#search,#role,#sort').forEach(el => {
            el.addEventListener('input', debouncedLoad);
            el.addEventListener('change', debouncedLoad);
        });

        // Handle pagination clicks (event delegation)
        document.addEventListener('click', async function(e){
            const link = e.target.closest('.pagination a');
            if (link) {
                e.preventDefault();
                const url = new URL(link.href);
                // preserve current form params
                const formParams = new URLSearchParams(new FormData(form));
                formParams.forEach((v,k)=> url.searchParams.set(k,v));
                const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                usersCard.innerHTML = data.html;
            }
        });

        // Delegated handlers for action buttons
        document.addEventListener('click', function(e){
            const btnView = e.target.closest('.btn-view-user');
            const btnEdit = e.target.closest('.btn-edit-user');
            const btnDelete = e.target.closest('.btn-delete-user');
            if (btnView) {
                const id = btnView.getAttribute('data-user-id');
                viewUser(id);
            } else if (btnEdit) {
                const id = btnEdit.getAttribute('data-user-id');
                editUser(id);
            } else if (btnDelete) {
                const id = btnDelete.getAttribute('data-user-id');
                const name = btnDelete.getAttribute('data-user-name');
                deleteUser(id, name);
            }
        });
    })();
    let currentUserId = null;

    // Delete user function
    function deleteUser(userId, userName) {
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteForm').action = `/admin/users/${userId}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // View user function
    async function viewUser(userId) {
        try {
            const response = await fetch(`/admin/users/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const user = await response.json();
            
            document.getElementById('view_profile_picture').src = user.profile_picture_url;
            document.getElementById('view_name').textContent = user.name;
            document.getElementById('view_email').textContent = user.email;
            document.getElementById('view_phone').textContent = user.phone || 'Not provided';
            document.getElementById('view_bio').textContent = user.bio || 'No bio available';
            document.getElementById('view_created_at').textContent = new Date(user.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('view_updated_at').textContent = new Date(user.updated_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            const roleBadge = document.getElementById('view_role_badge');
            roleBadge.className = `badge fs-6 bg-${user.role === 'admin' ? 'danger' : 'primary'}`;
            roleBadge.innerHTML = `<i class="fas fa-${user.role === 'admin' ? 'shield-alt' : 'user'} me-1"></i>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}`;
            
            currentUserId = userId;
            new bootstrap.Modal(document.getElementById('viewUserModal')).show();
        } catch (error) {
            alert('Error loading user details');
            console.error(error);
        }
    }

    // Edit user function
    async function editUser(userId) {
        try {
            const response = await fetch(`/admin/users/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const user = await response.json();
            
            document.getElementById('edit_current_profile_picture').src = user.profile_picture_url;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_phone').value = user.phone || '';
            document.getElementById('edit_role').value = user.role;
            document.getElementById('edit_bio').value = user.bio || '';
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
            
            document.getElementById('editUserForm').action = `/admin/users/${userId}`;
            currentUserId = userId;
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        } catch (error) {
            alert('Error loading user details');
            console.error(error);
        }
    }

    // Edit from view modal
    function editUserFromView() {
        bootstrap.Modal.getInstance(document.getElementById('viewUserModal')).hide();
        setTimeout(() => {
            editUser(currentUserId);
        }, 300);
    }

    // Form submissions
    document.getElementById('createUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitForm(this, 'POST');
    });

    document.getElementById('editUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitForm(this, 'POST');
    });

    async function submitForm(form, method) {
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
        submitButton.disabled = true;

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok) {
                // Success
                bootstrap.Modal.getInstance(form.closest('.modal')).hide();
                location.reload(); // Refresh page to show updated data
            } else {
                // Validation errors
                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.parentElement.querySelector('.invalid-feedback');
                            if (feedback) {
                                feedback.textContent = result.errors[key][0];
                            }
                        }
                    });
                } else {
                    alert(result.message || 'An error occurred');
                }
            }
        } catch (error) {
            console.error(error);
            alert('Network error occurred');
        } finally {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    }

    // Select all functionality
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function selectAll() {
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        document.getElementById('selectAllCheckbox').checked = true;
    }

    function bulkDelete() {
        const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
        if (selectedUsers.length === 0) {
            alert('Please select at least one user to delete.');
            return;
        }
        
        if (confirm(`Are you sure you want to delete ${selectedUsers.length} selected user(s)? This action cannot be undone!`)) {
            const userIds = Array.from(selectedUsers).map(checkbox => checkbox.value);
            
            fetch('/admin/users/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ user_ids: userIds })
            })
            .then(response => response.json())
            .then(result => {
                if (result.message) {
                    alert(result.message);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting users.');
            });
        }
    }

    // Update select all checkbox state based on individual checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-checkbox')) {
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            
            if (checkedCheckboxes.length === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            } else if (checkedCheckboxes.length === userCheckboxes.length) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
        }
    });

    // Password confirmation validation
    document.getElementById('create_password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('create_password').value;
        const confirmation = this.value;
        
        if (password !== confirmation) {
            this.classList.add('is-invalid');
            this.parentElement.querySelector('.invalid-feedback').textContent = 'Passwords do not match';
        } else {
            this.classList.remove('is-invalid');
            this.parentElement.querySelector('.invalid-feedback').textContent = '';
        }
    });

    document.getElementById('edit_password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('edit_password').value;
        const confirmation = this.value;
        
        if (password && password !== confirmation) {
            this.classList.add('is-invalid');
            this.parentElement.querySelector('.invalid-feedback').textContent = 'Passwords do not match';
        } else {
            this.classList.remove('is-invalid');
            this.parentElement.querySelector('.invalid-feedback').textContent = '';
        }
    });
</script>
@endsection
