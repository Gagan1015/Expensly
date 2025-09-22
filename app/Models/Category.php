<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'color', 'description'
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Scopes
    public function scopeGlobal($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        });
    }

    // Accessors
    public function getIsGlobalAttribute()
    {
        return is_null($this->user_id);
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('amount');
    }
}