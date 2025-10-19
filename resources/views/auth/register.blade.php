@extends('auth.layouts.app')

@section('title', 'Register - Expensly')

@section('content')
<!-- Enhanced registration page with modern design elements -->
<div class="relative min-h-screen flex items-center justify-center px-4 py-20 overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-background to-accent/5"></div>
    <div class="absolute top-20 left-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-conic from-primary/5 via-transparent to-accent/5 rounded-full blur-3xl opacity-30"></div>
    
    <!-- Floating geometric shapes -->
    <div class="absolute top-32 right-20 w-4 h-4 bg-primary/20 rounded-full animate-bounce delay-500"></div>
    <div class="absolute bottom-32 left-20 w-6 h-6 bg-accent/20 rotate-45 animate-pulse delay-700"></div>
    <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-primary/30 rounded-full animate-ping delay-1000"></div>
    <div class="absolute bottom-1/3 left-1/4 w-5 h-5 bg-accent/30 rotate-12 animate-bounce delay-300"></div>
    
    <!-- Auth card with enhanced styling -->
    <div class="relative z-10 w-full max-w-lg">
        <!-- Main card with enhanced styling -->
        <div class="bg-card/95 rounded-2xl border border-border/50 shadow-2xl shadow-primary/10 overflow-hidden backdrop-blur-md hover:shadow-3xl hover:shadow-primary/20 transition-all duration-500">
            <!-- Header section with gradient background -->
            <div class="relative overflow-hidden px-8 py-12">
                <!-- Added gradient background matching landing page design -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-accent/10"></div>
                
                <div class="relative z-10 text-center space-y-4">
                    <!-- Icon badge with register icon -->
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-primary/20 to-accent/20 border border-primary/30 mx-auto shadow-lg">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    
                    <!-- Heading -->
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold text-foreground">Create Account</h1>
                        <p class="text-muted-foreground text-sm">Join thousands managing their expenses</p>
                    </div>
                </div>
            </div>

            <!-- Form section -->
            <div class="px-8 py-8 space-y-6">
                <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                    @csrf

                    <!-- Name Field -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-foreground">
                            Full Name
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                placeholder="Enter your full name"
                                class="w-full px-4 py-3 rounded-lg border border-border bg-input text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-200 @error('name') ring-2 ring-destructive/50 border-destructive @enderror"
                            >
                            <!-- Added icon for name field -->
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-destructive flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-foreground">
                            Email Address
                        </label>
                        <div class="relative">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="you@example.com"
                                class="w-full px-4 py-3 rounded-lg border border-border bg-input text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-200 @error('email') ring-2 ring-destructive/50 border-destructive @enderror"
                            >
                            <!-- Added icon for email field -->
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-destructive flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-foreground">
                            Password
                        </label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                                minlength="8"
                                class="w-full px-4 py-3 pr-12 rounded-lg border border-border bg-input text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-200 @error('password') ring-2 ring-destructive/50 border-destructive @enderror"
                            >
                            <!-- Improved password toggle with Alpine.js -->
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors p-1"
                                tabindex="-1"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-muted-foreground">Password must be at least 8 characters long</p>
                        @error('password')
                            <p class="mt-1 text-sm text-destructive flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-foreground">
                            Confirm Password
                        </label>
                        <div class="relative" x-data="{ showPasswordConfirm: false }">
                            <input
                                :type="showPasswordConfirm ? 'text' : 'password'"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                                minlength="8"
                                class="w-full px-4 py-3 pr-12 rounded-lg border border-border bg-input text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-200 @error('password_confirmation') ring-2 ring-destructive/50 border-destructive @enderror"
                            >
                            <!-- Improved password toggle with Alpine.js -->
                            <button
                                type="button"
                                @click="showPasswordConfirm = !showPasswordConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors p-1"
                                tabindex="-1"
                            >
                                <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-destructive flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start gap-3">
                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            required
                            class="mt-1 w-4 h-4 rounded border-border bg-input text-primary focus:ring-2 focus:ring-primary/50 cursor-pointer accent-primary"
                        >
                        <label for="terms" class="text-sm text-foreground cursor-pointer leading-relaxed">
                            I agree to the 
                            <a href="#" class="text-primary hover:text-primary/80 font-medium transition-colors">Terms of Service</a> 
                            and 
                            <a href="#" class="text-primary hover:text-primary/80 font-medium transition-colors">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full px-4 py-3 rounded-lg font-semibold text-primary-foreground bg-gradient-to-r from-primary to-accent hover:shadow-lg hover:shadow-primary/25 transition-all duration-200 transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2"
                    >
                        <span>Create Account</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-border"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-3 bg-card text-muted-foreground font-medium">Already have an account?</span>
                    </div>
                </div>

                <!-- Login Link -->
                <a
                    href="{{ route('login') }}"
                    class="block w-full px-4 py-3 rounded-lg font-semibold text-center border-2 border-primary text-primary hover:bg-primary/5 transition-all duration-200"
                >
                    Sign In Instead
                </a>
            </div>
        </div>

        <!-- Footer Text -->
        <p class="text-center text-xs text-muted-foreground mt-6 space-x-1">
            <span>By creating an account, you agree to our</span>
            <a href="#" class="text-primary hover:text-primary/80 font-medium transition-colors">Terms</a>
            <span>and</span>
            <a href="#" class="text-primary hover:text-primary/80 font-medium transition-colors">Privacy</a>
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility for password field
    document.getElementById('togglePassword').addEventListener('click', function () {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Toggle password visibility for confirmation field
    document.getElementById('togglePasswordConfirm').addEventListener('click', function () {
        const password = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Password strength indicator (optional)
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strength = document.getElementById('password-strength') || createStrengthIndicator();
        
        let score = 0;
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        const levels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        const colors = ['danger', 'warning', 'info', 'primary', 'success'];
        
        if (password.length > 0) {
            strength.className = `progress-bar bg-${colors[score - 1]}`;
            strength.style.width = `${(score / 5) * 100}%`;
            strength.textContent = levels[score - 1] || 'Very Weak';
        } else {
            strength.style.width = '0%';
            strength.textContent = '';
        }
    });

    function createStrengthIndicator() {
        const container = document.getElementById('password').parentNode.parentNode;
        const progressDiv = document.createElement('div');
        progressDiv.innerHTML = `
            <div class="progress mt-1" style="height: 4px;">
                <div class="progress-bar" role="progressbar" id="password-strength"></div>
            </div>
        `;
        container.appendChild(progressDiv);
        return document.getElementById('password-strength');
    }
</script>
@endsection