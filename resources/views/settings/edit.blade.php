@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card admin-card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Account Settings</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-12 text-center mb-3">
                        <img src="{{ $user->profile_picture_url }}" alt="Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" name="bio" rows="3" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" minlength="8" placeholder="Leave blank to keep current">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="password_confirmation" minlength="8">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" name="profile_picture" accept="image/*">
                        <div class="form-text">JPG, PNG, GIF. Max size 2MB.</div>
                    </div>

                    <div class="col-12 d-flex justify-content-end mt-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2"><i class="fas fa-arrow-left me-1"></i>Back</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


