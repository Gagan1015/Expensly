@extends('layouts.app')

@section('title', 'Login - Laravel Auth')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card">
            <div class="card-header bg-primary text-white text-center py-4">
                <i class="fas fa-sign-in-alt fa-2x mb-2"></i>
                <h4 class="mb-0">Welcome Back</h4>
                <p class="mb-0 opacity-75">Sign in to your account</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login.post') }}" novalidate>
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email Address
                        </label>
                        <input 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email"
                            placeholder="Enter your email"
                        >
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <div class="input-group">
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="Enter your password"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="remember" 
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                <i class="fas fa-remember me-1"></i>Remember me
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-custom btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </div>
                </form>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="mb-0">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-decoration-none">
                            <i class="fas fa-user-plus me-1"></i>Create one here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
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
</script>
@endsection