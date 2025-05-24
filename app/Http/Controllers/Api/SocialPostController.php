<?php

namespace App\Http\Controllers\Api;

use App\Models\SocialPost;
use Illuminate\Http\Request;

class SocialPostController
{
    public function index(Request $request)
    {
        return $request->user()->socialPosts;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        return $request->user()->socialPosts()->create($data);
    }

    // Add show, update, and destroy methods
}