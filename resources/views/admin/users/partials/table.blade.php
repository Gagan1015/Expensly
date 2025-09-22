<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="50">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                    </th>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" class="profile-img me-3">
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    @if($user->bio)
                                        <small class="text-muted">{{ Str::limit($user->bio, 50) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <i class="fas fa-envelope me-1 text-muted"></i>{{ $user->email }}
                            </div>
                            @if($user->phone)
                                <div>
                                    <i class="fas fa-phone me-1 text-muted"></i>{{ $user->phone }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} fs-6">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'shield-alt' : 'user' }} me-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Active
                            </span>
                        </td>
                        <td>
                            <div>{{ $user->created_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info btn-view-user" data-user-id="{{ $user->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning btn-edit-user" data-user-id="{{ $user->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                    <button class="btn btn-outline-danger btn-delete-user" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">No users found</h5>
                            <p class="text-muted">There are no users matching your criteria.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                <i class="fas fa-user-plus me-2"></i>Add First User
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($users->hasPages())
    <div class="card-footer bg-light">
        {{ $users->appends(request()->query())->links() }}
    </div>
@endif

