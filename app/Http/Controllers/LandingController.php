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
        // Debug: Check if view file exists
        $viewPath = resource_path('views/landing.blade.php');
        
        if (!file_exists($viewPath)) {
            return response()->json([
                'error' => 'View file not found',
                'path' => $viewPath,
                'resource_path' => resource_path(),
                'views_dir_exists' => is_dir(resource_path('views')),
                'views_contents' => is_dir(resource_path('views')) ? scandir(resource_path('views')) : 'Directory not found'
            ], 500);
        }
        
        return view('landing');
    }
}
