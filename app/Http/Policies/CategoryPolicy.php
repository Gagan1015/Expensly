<?php
// app/Policies/CategoryPolicy.php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function update(User $user, Category $category)
    {
        // Users can only edit their own categories, admins can edit global categories
        return ($category->user_id === $user->id) || 
               ($category->is_global && $user->isAdmin());
    }

    public function delete(User $user, Category $category)
    {
        // Users can only delete their own categories, admins can delete global categories
        return ($category->user_id === $user->id) || 
               ($category->is_global && $user->isAdmin());
    }
}