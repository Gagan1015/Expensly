<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Laravel Auth')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-expanded-width: 20vw;
            --sidebar-collapsed-width: 72px;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow-y: auto;
            overflow-x: hidden;
            width: var(--sidebar-expanded-width);
            min-width: 220px;
            max-width: 360px;
            transition: width 0.25s ease;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 15px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }
        .main-content {
            margin-left: clamp(220px, var(--sidebar-expanded-width), 360px);
            transition: margin-left 0.25s ease;
        }
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        .admin-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .table th {
            border: none;
            background: #f8f9fa;
            font-weight: 600;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .badge {
            font-size: 0.75em;
        }
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
            min-width: var(--sidebar-collapsed-width);
            max-width: var(--sidebar-collapsed-width);
        }
        .sidebar.collapsed .navbar-brand .nav-text,
        .sidebar.collapsed .nav .nav-text,
        .sidebar.collapsed hr {
            display: none !important;
        }
        .sidebar.collapsed .p-3 { padding-left: 0.5rem; padding-right: 0.5rem; }
        .sidebar.collapsed .nav-link { margin: 6px auto; padding: 12px 0; text-align: center; justify-content: center; }
        .sidebar.collapsed .nav i { margin-right: 0 !important; }
        .sidebar .nav i {
            width: 20px;
            text-align: center;
        }
        .sidebar .navbar-brand { display: flex; align-items: center; }
        .sidebar.collapsed .navbar-brand { justify-content: center; }
        .sidebar.collapsed .navbar-brand i { margin-right: 0 !important; }
        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="p-3">
            <h4 class="navbar-brand mb-0 d-flex align-items-center">
                <i class="fas fa-shield-alt me-2"></i><span class="nav-text">Admin Panel</span>
            </h4>
        </div>
        
        <div class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i><span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i><span class="nav-text">Users Management</span>
            </a>
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="fas fa-home me-2"></i><span class="nav-text">Back to Site</span>
            </a>
            <hr class="text-white-50 mx-3">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="nav-link btn text-start w-100 border-0" style="background: none;">
                    <i class="fas fa-sign-out-alt me-2"></i><span class="nav-text">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary" type="button" id="sidebarToggle" title="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->profile_picture_url }}" alt="Profile" class="profile-img me-2">
                            {{ Auth::user()->name }}
                            <span class="badge bg-danger ms-2">Admin</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-home me-2"></i>Main Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid py-4">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function(){
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('.main-content');
            const btn = document.getElementById('sidebarToggle');

            // apply saved state
            const saved = localStorage.getItem('adminSidebarCollapsed') === '1';
            if (saved) {
                sidebar.classList.add('collapsed');
                main.classList.add('sidebar-collapsed');
            }

            btn.addEventListener('click', function(){
                const collapsed = sidebar.classList.toggle('collapsed');
                main.classList.toggle('sidebar-collapsed', collapsed);
                localStorage.setItem('adminSidebarCollapsed', collapsed ? '1' : '0');
            });

            // Mobile slide-in behavior
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isToggleButton = event.target.closest('#sidebarToggle');
                if (!isClickInsideSidebar && !isToggleButton && window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                }
            });
        })();
    </script>
    @yield('scripts')
</body>
</html>