<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialPostController extends Controller
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

    public function show($id)
    {
        $post = auth()->user()->socialPosts()->findOrFail($id);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = auth()->user()->socialPosts()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = auth()->user()->socialPosts()->findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }
}