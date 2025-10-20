<!-- Modern Sidebar Component -->
<div x-data="{ 
        sidebarOpen: window.innerWidth >= 1024, 
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' 
     }" 
     x-init="
        $watch('sidebarCollapsed', value => {
            localStorage.setItem('sidebarCollapsed', value);
            // Trigger a custom event to notify main content
            window.dispatchEvent(new CustomEvent('sidebar-toggle', { detail: { collapsed: value } }));
        })
     "
     class="relative">
    
    <!-- Sidebar Overlay (Mobile) -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <div x-show="sidebarOpen || window.innerWidth >= 1024"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         :class="sidebarCollapsed ? 'w-16' : 'w-64'"
         class="sidebar-fixed bg-gradient-to-b from-blue-600 to-blue-800 shadow-xl transition-all duration-300 ease-in-out">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-blue-500/30">
            <a href="{{ route('landing') }}" 
               :class="sidebarCollapsed ? 'justify-center' : 'justify-start'"
               class="flex items-center gap-3 text-white hover:opacity-80 transition-opacity">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <span class="text-white font-bold text-lg">E</span>
                </div>
                <span x-show="!sidebarCollapsed" 
                      x-transition:enter="transition-opacity duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      x-transition:leave="transition-opacity duration-100"
                      x-transition:leave-start="opacity-100"
                      x-transition:leave-end="opacity-0"
                      class="text-xl font-bold">Expensly</span>
            </a>
            
            <!-- Collapse Button (Desktop) -->
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden lg:flex items-center justify-center w-8 h-8 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-6 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center gap-3 px-3 py-2.5 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                </svg>
                <span x-show="!sidebarCollapsed" 
                      x-transition:enter="transition-opacity duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      x-transition:leave="transition-opacity duration-100"
                      x-transition:leave-start="opacity-100"
                      x-transition:leave-end="opacity-0"
                      class="font-medium">Dashboard</span>
            </a>

            <!-- My Expenses -->
            <a href="{{ route('expenses.index') }}" 
               class="group flex items-center gap-3 px-3 py-2.5 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="!sidebarCollapsed" 
                      x-transition:enter="transition-opacity duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      x-transition:leave="transition-opacity duration-100"
                      x-transition:leave-start="opacity-100"
                      x-transition:leave-end="opacity-0"
                      class="font-medium">My Expenses</span>
            </a>

            <!-- Categories -->
            <a href="{{ route('categories.index') }}" 
               class="group flex items-center gap-3 px-3 py-2.5 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span x-show="!sidebarCollapsed" 
                      x-transition:enter="transition-opacity duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      x-transition:leave="transition-opacity duration-100"
                      x-transition:leave-start="opacity-100"
                      x-transition:leave-end="opacity-0"
                      class="font-medium">Categories</span>
            </a>

            <!-- Reports -->
            <a href="{{ route('expenses.reports') }}" 
               class="group flex items-center gap-3 px-3 py-2.5 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 {{ request()->routeIs('expenses.reports') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span x-show="!sidebarCollapsed" 
                      x-transition:enter="transition-opacity duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      x-transition:leave="transition-opacity duration-100"
                      x-transition:leave-start="opacity-100"
                      x-transition:leave-end="opacity-0"
                      class="font-medium">Reports</span>
            </a>

            @if(Auth::user()->isAdmin())
            <!-- Divider -->
            <div x-show="!sidebarCollapsed" class="border-t border-white/20 my-4"></div>
            
            <!-- Admin Panel -->
            <a href="{{ route('admin.dashboard') }}" 
               class="group flex items-center gap-3 px-3 py-2.5 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="!sidebarCollapsed" 
                      x-transition:enter="transition-opacity duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      x-transition:leave="transition-opacity duration-100"
                      x-transition:leave-start="opacity-100"
                      x-transition:leave-end="opacity-0"
                      class="font-medium">Admin Panel</span>
            </a>
            @endif
        </nav>

        <!-- Logout Section -->
        <div class="p-3 border-t border-white/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="group flex items-center gap-3 w-full px-3 py-2.5 text-white/90 hover:text-white hover:bg-red-500/20 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="!sidebarCollapsed" 
                          x-transition:enter="transition-opacity duration-200 delay-100"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          x-transition:leave="transition-opacity duration-100"
                          x-transition:leave-start="opacity-100"
                          x-transition:leave-end="opacity-0"
                          class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile Sidebar Toggle -->
    <button @click="sidebarOpen = !sidebarOpen"
            class="fixed top-4 left-4 z-[60] lg:hidden flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition-colors">
        <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
