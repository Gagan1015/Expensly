@extends('auth.layouts.app')

@section('title', 'Login - Expensly')

@section('content')
<!-- Modern minimalist auth page with sophisticated design -->
<div class="relative min-h-screen flex items-center justify-center px-4 py-20 overflow-hidden bg-gradient-to-br from-background via-background to-background">
    <!-- Simplified background with subtle blur elements for minimalist aesthetic -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/3 via-transparent to-accent/3"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent/5 rounded-full blur-3xl"></div>
    
    <!-- Auth card container -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Main card with enhanced styling -->
        <div class="bg-card rounded-2xl border border-border/40 shadow-xl shadow-primary/5 overflow-hidden backdrop-blur-sm hover:shadow-2xl hover:shadow-primary/10 transition-all duration-500">
            
            <!-- Header section with gradient -->
            <div class="relative px-8 py-12 bg-gradient-to-br from-primary/8 via-transparent to-accent/8 border-b border-border/30">
                <div class="text-center space-y-4">
                    <!-- Icon badge -->
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-primary/15 to-accent/15 border border-primary/20 mx-auto">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    
                    <!-- Heading -->
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold text-foreground">Welcome Back</h1>
                        <p class="text-muted-foreground text-sm font-medium">Sign in to your account</p>
                    </div>
                </div>
            </div>

            <!-- Form section -->
            <div class="px-8 py-8 space-y-6">
                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

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
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-foreground">
                                Password
                            </label>
                            <a href="#" class="text-xs font-medium text-primary hover:text-primary/80 transition-colors">
                                Forgot?
                            </a>
                        </div>
                        <div class="relative" x-data="{ showPassword: false }">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full px-4 py-3 pr-12 rounded-lg border border-border bg-input text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-200 @error('password') ring-2 ring-destructive/50 border-destructive @enderror"
                            >
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
                        @error('password')
                            <p class="mt-1 text-sm text-destructive flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-3">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-border bg-input text-primary focus:ring-2 focus:ring-primary/50 cursor-pointer accent-primary"
                        >
                        <label for="remember" class="text-sm text-foreground cursor-pointer font-medium">
                            Keep me signed in
                        </label>
                    </div>

                    <!-- Updated submit button with refined styling -->
                    <button
                        type="submit"
                        class="w-full px-4 py-3 rounded-lg font-semibold text-primary-foreground bg-gradient-to-r from-primary to-accent hover:shadow-lg hover:shadow-primary/20 transition-all duration-200 transform hover:scale-[1.01] active:scale-95"
                    >
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-border/50"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-3 bg-card text-muted-foreground font-medium">New to Expensly?</span>
                    </div>
                </div>

                <!-- Register Link -->
                <a
                    href="{{ route('register') }}"
                    class="block w-full px-4 py-3 rounded-lg font-semibold text-center border-2 border-primary/30 text-primary hover:bg-primary/5 hover:border-primary/50 transition-all duration-200"
                >
                    Create an Account
                </a>
            </div>
        </div>

        <!-- Footer Text -->
        <p class="text-center text-xs text-muted-foreground mt-6 space-x-1">
            <span>By signing in, you agree to our</span>
            <a href="#" class="text-primary hover:text-primary/80 font-medium transition-colors">Terms</a>
            <span>and</span>
            <a href="#" class="text-primary hover:text-primary/80 font-medium transition-colors">Privacy</a>
        </p>
    </div>
</div>
@endsection
