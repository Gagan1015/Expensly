<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.users.partials.table', compact('users'))
                    ->with(['query' => $request->query()])
                    ->render(),
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Not needed for modal version
        return redirect()->route('admin.users.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'role' => 'required|in:user,admin',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'bio' => $request->bio,
            'role' => $request->role,
        ];

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        User::create($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'User created successfully!']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'bio' => $user->bio,
                'role' => $user->role,
                'profile_picture_url' => $user->profile_picture_url,
                'created_at' => $user->created_at->toISOString(),
                'updated_at' => $user->updated_at->toISOString(),
            ]);
        }

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Not needed for modal version
        return redirect()->route('admin.users.index');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|confirmed|min:8',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'role' => 'required|in:user,admin',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        $user->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'User updated successfully!']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::user()->id) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'You cannot delete your own account!'], 422);
            }
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        // Delete profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'User deleted successfully!']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    // Bulk operations
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->user_ids;
        $userIds = $request->user_ids;
        // Remove current user from deletion list
        $userIds = array_filter($userIds, function($id) {
            return $id != Auth::user()->id;
        });

        if (empty($userIds)) {
            return response()->json(['message' => 'No valid users selected for deletion'], 422);
        }
        // Get users and delete their profile pictures
        $users = User::whereIn('id', $userIds)->get();
        foreach ($users as $user) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
        }

        // Delete users
        User::whereIn('id', $userIds)->delete();

        return response()->json(['message' => count($userIds) . ' users deleted successfully!']);
    }
}