<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserBudget extends Model
{
    protected $fillable = [
        'user_id',
        'monthly_budget',
        'year',
        'month'
    ];

    protected $casts = [
        'monthly_budget' => 'decimal:2',
        'year' => 'integer',
        'month' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getCurrentBudget($userId)
    {
        $now = Carbon::now();
        return self::where('user_id', $userId)
                  ->where('year', $now->year)
                  ->where('month', $now->month)
                  ->first();
    }

    public static function getOrCreateCurrentBudget($userId, $defaultAmount = 0)
    {
        $now = Carbon::now();
        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'year' => $now->year,
                'month' => $now->month
            ],
            [
                'monthly_budget' => $defaultAmount
            ]
        );
    }
}
