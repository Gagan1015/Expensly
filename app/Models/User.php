<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'bio',
        'phone',
        'role',
    ];

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Get profile picture URL with fallback
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }

        // Default avatar using user's initials
        $initials = strtoupper(substr($this->name, 0, 1));
        if (strpos($this->name, ' ') !== false) {
            $nameParts = explode(' ', $this->name);
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&color=7F9CF5&background=EBF4FF&size=200';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function budgets()
    {
        return $this->hasMany(UserBudget::class);
    }

    // Helper methods
    public function getTotalExpensesThisMonth()
    {
        return $this->expenses()->thisMonth()->sum('amount');
    }

    public function getTotalExpensesThisYear()
    {
        return $this->expenses()->thisYear()->sum('amount');
    }

    public function getExpensesByCategory()
    {
        return $this->expenses()
            ->selectRaw('category_id, SUM(amount) as total')
            ->with('category')
            ->groupBy('category_id')
            ->get();
    }
}
