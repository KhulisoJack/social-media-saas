<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class DashboardController
{
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'total_requests' => $user->generationRequests()->count(),
            'total_saved' => $user->socialPosts()->count(),
            'last_generated' => $user->generationRequests()->latest()->first()?->created_at,
            'recent_posts' => $user->socialPosts()->latest()->limit(5)->get()
        ]);
    }
}