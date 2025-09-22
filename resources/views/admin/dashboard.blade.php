@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h2>
    <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>{{ now()->format('F j, Y') }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h3 class="mb-1">{{ $totalUsers }}</h3>
                <p class="mb-0">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-shield-alt fa-3x mb-3"></i>
                <h3 class="mb-1">{{ $adminUsers }}</h3>
                <p class="mb-0">Admin Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-user fa-3x mb-3"></i>
                <h3 class="mb-1">{{ $regularUsers }}</h3>
                <p class="mb-0">Regular Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <h3 class="mb-1">{{ now()->format('H:i') }}</h3>
                <p class="mb-0">Current Time</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card admin-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-success">
                        <i class="fas fa-user-plus me-2"></i>Add New User
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-info">
                        <i class="fas fa-home me-2"></i>Back to Main Site
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card admin-card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>System Overview</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-success">{{ number_format(($regularUsers / $totalUsers) * 100, 1) }}%</h4>
                            <small class="text-muted">Regular Users</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-danger">{{ number_format(($adminUsers / $totalUsers) * 100, 1) }}%</h4>
                        <small class="text-muted">Admin Users</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="card admin-card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-user-clock me-2"></i>Recent Users</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
            View All Users
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" class="profile-img me-3">
                                    <div>
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        @if($user->bio)
                                            <small class="text-muted">{{ Str::limit($user->bio, 30) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                    <i class="fas fa-{{ $user->role === 'admin' ? 'shield-alt' : 'user' }} me-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $user->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No users found</h5>
                                <p class="text-muted">Start by creating your first user.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh time every minute
    setInterval(function() {
        const timeElement = document.querySelector('.stat-card:last-child h3');
        if (timeElement) {
            const now = new Date();
            timeElement.textContent = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: false 
            });
        }
    }, 60000);

    // Add some interactive effects
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>
@endsection
