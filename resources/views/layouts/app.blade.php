<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(session('success'))
        <meta name="flash-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="flash-error" content="{{ session('error') }}">
    @endif
    <title>@yield('title', 'Laravel Custom Auth')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Ensure sidebar positioning works in production */
        .sidebar-fixed {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            z-index: 50 !important;
        }

        /* Main content margins for sidebar */
        .main-content-with-sidebar {
            margin-left: 16rem; /* 64 * 0.25rem = 16rem for w-64 */
            transition: margin-left 0.3s ease;
        }

        .main-content-with-sidebar.collapsed {
            margin-left: 4rem; /* 16 * 0.25rem = 4rem for w-16 */
        }

        @media (max-width: 1024px) {
            .main-content-with-sidebar,
            .main-content-with-sidebar.collapsed {
                margin-left: 0 !important;
            }
        }
    </style>

    <!-- Page-specific styles -->
    @stack('styles')
</head>

<body class="bg-gray-50 font-sans antialiased">
    @auth
        <div class="min-h-screen bg-gray-50" x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
             x-init="
                window.addEventListener('sidebar-toggle', (e) => {
                    sidebarCollapsed = e.detail.collapsed;
                });
             ">
            <!-- Modern Sidebar Component -->
            @include('components.sidebar')
            
            <!-- Main Content Area -->
            <div class="main-content-with-sidebar"
                 :class="sidebarCollapsed ? 'collapsed' : ''">
                
                <!-- Modern Navbar Component -->
                @include('components.navbar')
                
                <!-- Page Content -->
                <main class="min-h-screen bg-gray-50">
                    <div class="px-4 sm:px-6 lg:px-8 py-6">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    @endauth
    
    @guest
        <!-- Guest Content -->
        <main class="gradient-bg d-flex align-items-center min-h-screen">
            <div class="container py-5">
                @yield('content')
            </div>
        </main>
    @endguest

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Toast Notification Function -->
    <script>
        function showToast(message, type = 'info') {
            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
                document.body.appendChild(toastContainer);
            }

            // Create toast
            const toastId = 'toast-' + Date.now();
            const bgClass = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            const iconClass = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';

            const toastHTML = `
                <div id="${toastId}" class="flex items-center p-4 mb-4 text-white rounded-lg shadow-lg ${bgClass} max-w-xs" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-${iconClass} mr-2"></i>
                        <span class="text-sm font-medium">${message}</span>
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-white hover:text-gray-200 rounded-lg focus:ring-2 focus:ring-white p-1.5 inline-flex h-8 w-8" onclick="this.parentElement.remove()">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);

            // Auto-remove toast after 5 seconds
            setTimeout(() => {
                const toastElement = document.getElementById(toastId);
                if (toastElement) {
                    toastElement.remove();
                }
            }, 5000);
        }

        // Handle Laravel flash messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for flash messages from meta tags
            const successMessage = document.querySelector('meta[name="flash-success"]');
            const errorMessage = document.querySelector('meta[name="flash-error"]');
            
            if (successMessage) {
                showToast(successMessage.getAttribute('content'), 'success');
            }
            
            if (errorMessage) {
                showToast(errorMessage.getAttribute('content'), 'error');
            }
        });
    </script>

    <!-- Page-specific scripts -->
    @stack('scripts')
    @yield('scripts')
</body>

</html>