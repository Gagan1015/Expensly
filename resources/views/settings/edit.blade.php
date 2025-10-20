@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h5 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-cog mr-2 text-gray-600"></i>
                    Account Settings
                </h5>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Picture -->
                <div class="flex justify-center mb-6">
                    <img src="{{ $user->profile_picture_url }}" 
                         alt="Profile" 
                         class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                    </div>

                    <!-- Phone -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}">
                    </div>

                    <!-- Bio (Full Width) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none" 
                                  name="bio" 
                                  rows="3" 
                                  maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <!-- New Password -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" 
                               name="password" 
                               minlength="8" 
                               placeholder="Leave blank to keep current">
                    </div>

                    <!-- Confirm New Password -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" 
                               name="password_confirmation" 
                               minlength="8">
                    </div>

                    <!-- Profile Picture Upload -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                        <input type="file" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" 
                               name="profile_picture" 
                               accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF. Max size 2MB.</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end items-center mt-6 pt-4 border-t border-gray-200 space-x-3">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection