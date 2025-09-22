<?php
// app/Policies/ExpensePolicy.php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Expense $expense)
    {
        return $user->id === $expense->user_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Expense $expense)
    {
        return $user->id === $expense->user_id || $user->isAdmin();
    }

    public function delete(User $user, Expense $expense)
    {
        return $user->id === $expense->user_id || $user->isAdmin();
    }
}