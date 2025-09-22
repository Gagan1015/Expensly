@extends('layouts.app')

@section('title', 'Register - Laravel Auth')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card">
            <div class="card-header bg-success text-white text-center py-4">
                <i class="fas fa-user-plus fa-2x mb-2"></i>
                <h4 class="mb-0">Create Account</h4>
                <p class="mb-0 opacity-75">Join our platform today</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register.post') }}" novalidate>
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Full Name
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autocomplete="name"
                            placeholder="Enter your full name"
                        >
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

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
                                autocomplete="new-password"
                                placeholder="Enter your password"
                                minlength="8"
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
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Password must be at least 8 characters long
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>Confirm Password
                        </label>
                        <div class="input-group">
                            <input 
                                type="password" 
                                class="form-control @error('password_confirmation') is-invalid @enderror" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Confirm your password"
                                minlength="8"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="terms" 
                                name="terms"
                                required
                            >
                            <label class="form-check-label" for="terms">
                                <i class="fas fa-check me-1"></i>I agree to the 
                                <a href="#" class="text-decoration-none">Terms of Service</a> and 
                                <a href="#" class="text-decoration-none">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-sign-in-alt me-1"></i>Sign in here
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