<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel Custom Auth')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .auth-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

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
            width: var(--sidebar-expanded-width);
            min-width: 220px;
            max-width: 360px;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width 0.25s ease;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .sidebar .nav i {
            width: 20px;
            text-align: center;
        }

        .sidebar .navbar-brand {
            color: #fff;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

    .main-bg {
        background: 
            radial-gradient(circle at 30% 70%, rgba(173, 216, 230, 0.35), transparent 60%),
            radial-gradient(circle at 70% 30%, rgba(255, 182, 193, 0.4), transparent 60%);
    }

        .main-content {
            margin-left: clamp(220px, var(--sidebar-expanded-width), 360px);
            transition: margin-left 0.25s ease;

        }

        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
            min-width: var(--sidebar-collapsed-width);
            max-width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .nav-text,
        .sidebar.collapsed hr {
            display: none !important;
        }

        .sidebar.collapsed .navbar-brand {
            justify-content: center;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
            margin: 6px auto;
        }

        /* Fixed toggle aligned to sidebar edge */
        #appSidebarToggle {
            /* position: relative; */
            top: 12px;
            left: calc(clamp(220px, var(--sidebar-expanded-width), 360px) + 12px);
            z-index: 1100;
        }

        body.app-sidebar-collapsed #appSidebarToggle {
            left: calc(var(--sidebar-collapsed-width) + 12px);
        }

        /* Push brand when toggle overlays it */
        .navbar .navbar-brand {
            margin-left: 48px;
        }

        body.app-sidebar-collapsed .navbar .navbar-brand {
            margin-left: 48px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            #appSidebarToggle {
                left: 12px;
            }
        }

        .navbar-brand {
            font-weight: bold;
        }

        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .alert {
            border: none;
            border-radius: 10px;
        }

        /* Expense Management Specific Styles */
        .expense-card {
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .expense-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .expense-amount {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .expense-category {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .expense-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .expense-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .category-badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }

        .category-food {
            background-color: #ff6b6b;
            color: white;
        }

        .category-transport {
            background-color: #4ecdc4;
            color: white;
        }

        .category-entertainment {
            background-color: #45b7d1;
            color: white;
        }

        .category-shopping {
            background-color: #f9ca24;
            color: #333;
        }

        .category-health {
            background-color: #6c5ce7;
            color: white;
        }

        .category-utilities {
            background-color: #a0e7e5;
            color: #333;
        }

        .category-other {
            background-color: #95a5a6;
            color: white;
        }
    </style>

    <!-- Page-specific styles -->
    @stack('styles')
</head>

<body class="bg-light">
    <!-- Sidebar (authenticated) -->
    @auth
    <nav class="sidebar" id="appSidebar">
        <div class="p-3">
            <h4 class="navbar-brand mb-0"><i class="fas fa-wallet me-2"></i><span class="nav-text">Expense Manager</span></h4>
        </div>
        <div class="nav flex-column">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i><span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i><span class="nav-text">My Expenses</span>
            </a>
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i><span class="nav-text">Categories</span>
            </a>
            <a href="{{ route('expenses.reports') }}" class="nav-link {{ request()->routeIs('expenses.reports') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i><span class="nav-text">Reports</span>
            </a>
            @if(Auth::user()->isAdmin())
            <hr class="text-white-50 mx-3">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-users-cog"></i><span class="nav-text">Admin Panel</span>
            </a>
            @endif
            <hr class="text-white-50 mx-3">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="nav-link btn text-start w-100 border-0" style="background: none;">
                    <i class="fas fa-sign-out-alt"></i><span class="nav-text">Logout</span>
                </button>
            </form>
        </div>
    </nav>
    <!-- Top bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm main-content">
        <div class="container">
            <button class="btn btn-outline-secondary me-2" type="button" id="appSidebarToggle" title="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" id="appTopbarBrand" href="{{ route('dashboard') }}">
                Laravel Auth
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->profile_picture_url }}" alt="Profile" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                            {{ Auth::user()->name }}
                            @if(Auth::user()->isAdmin())
                            <span class="badge bg-danger ms-2">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('settings.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Profile
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cogs me-2"></i>Admin Panel
                                </a>
                            </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="@guest gradient-bg d-flex align-items-center @else main-content @endguest main-bg">
        <div class="container @guest py-5 @else py-4 @endguest">
            @yield('content')
        </div>
    </main>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toast Notification Function -->
    <script>
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
                            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
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

        // Handle Laravel flash messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
            showToast("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
            showToast("{{ session('error') }}", 'error');
            @endif
        });
    </script>
    @auth
    <script>
        (function() {
            const sidebar = document.getElementById('appSidebar');
            const main = document.querySelectorAll('.main-content');
            const btn = document.getElementById('appSidebarToggle');
            const saved = localStorage.getItem('appSidebarCollapsed') === '1';
            if (saved) {
                sidebar.classList.add('collapsed');
                main.forEach(m => m.classList.add('sidebar-collapsed'));
                document.body.classList.add('app-sidebar-collapsed');
            }
            btn.addEventListener('click', function() {
                const collapsed = sidebar.classList.toggle('collapsed');
                main.forEach(m => m.classList.toggle('sidebar-collapsed', collapsed));
                localStorage.setItem('appSidebarCollapsed', collapsed ? '1' : '0');
                document.body.classList.toggle('app-sidebar-collapsed', collapsed);
            });
        })();
    </script>
    @endauth

    <!-- Page-specific scripts -->
    @stack('scripts')
    @yield('scripts')
</body>

</html>