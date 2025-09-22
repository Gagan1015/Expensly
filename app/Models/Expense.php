<?php
// app/Models/Expense.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'amount', 'description', 'date', 'payment_method', 'receipt_path'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'user_id' => 'integer',
        'category_id' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('date', now()->year);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereMonth('date', now()->subMonth()->month)
                    ->whereYear('date', now()->subMonth()->year);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('M d, Y');
    }
}