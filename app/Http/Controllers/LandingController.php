<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        // Debug: Return all diagnostic information
        return response()->json([
            'resource_path' => resource_path(),
            'views_path' => resource_path('views'),
            'landing_file_path' => resource_path('views/landing.blade.php'),
            'file_exists' => file_exists(resource_path('views/landing.blade.php')),
            'views_dir_exists' => is_dir(resource_path('views')),
            'views_readable' => is_readable(resource_path('views')),
            'views_contents' => is_dir(resource_path('views')) ? array_slice(scandir(resource_path('views')), 0, 20) : 'Directory not found',
            'view_paths' => config('view.paths', []),
            'app_path' => app_path(),
            'base_path' => base_path(),
        ]);
    }
}
