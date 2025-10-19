<nav class="sticky top-0 z-50 bg-background/95 backdrop-blur-sm border-b border-border" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                    <span class="text-primary-foreground font-bold text-lg">E</span>
                </div>
                <span class="text-xl font-bold text-foreground">Expensly</span>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-muted-foreground hover:text-foreground transition-colors text-sm font-medium">
                    Features
                </a>
                <a href="#testimonials" class="text-muted-foreground hover:text-foreground transition-colors text-sm font-medium">
                    Testimonials
                </a>
                <a href="#pricing" class="text-muted-foreground hover:text-foreground transition-colors text-sm font-medium">
                    Pricing
                </a>
            </div>

            <!-- Right Actions -->
            <div class="hidden md:flex items-center gap-4">
                <!-- Dark mode toggle with Alpine.js -->
                <button
                    @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    class="p-2 hover:bg-muted rounded-lg transition-colors"
                    aria-label="Toggle dark mode"
                >
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1m-16 0H1m15.364 1.636l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <a href="/login" class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
                    Login
                </a>
                <a href="/signup" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                    Sign Up
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center gap-2">
                <button
                    @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    class="p-2 hover:bg-muted rounded-lg transition-colors"
                    aria-label="Toggle dark mode"
                >
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1m-16 0H1m15.364 1.636l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-2 hover:bg-muted rounded-lg transition-colors"
                    aria-label="Toggle menu"
                >
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden border-t border-border py-4 space-y-3">
            <a href="#features" class="block px-4 py-2 text-muted-foreground hover:text-foreground transition-colors">
                Features
            </a>
            <a href="#testimonials" class="block px-4 py-2 text-muted-foreground hover:text-foreground transition-colors">
                Testimonials
            </a>
            <a href="#pricing" class="block px-4 py-2 text-muted-foreground hover:text-foreground transition-colors">
                Pricing
            </a>
            <div class="border-t border-border pt-3 px-4 space-y-2">
                <a href="/login" class="block py-2 text-muted-foreground hover:text-foreground transition-colors">
                    Login
                </a>
                <a href="/signup" class="block py-2 px-4 bg-primary text-primary-foreground rounded-lg text-center font-medium hover:opacity-90 transition-opacity">
                    Sign Up
                </a>
            </div>
        </div>
    </div>
</nav>
