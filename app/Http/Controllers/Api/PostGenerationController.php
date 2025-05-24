<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatGptService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostGenerationController extends Controller
{
    public function generate(Request $request, ChatGptService $chatGpt): JsonResponse
    {
        $request->validate(['topic' => 'required|string|max:255']);

        // Ensure the user is authenticated
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Generate content options
        $options = $chatGpt->generateContent($request->topic);

        // Log the generation request (if the relationship exists)
        if (method_exists($user, 'generationRequests')) {
            $user->generationRequests()->create([
                'topic' => $request->topic,
            ]);
        }

        return response()->json(['options' => $options]);
    }
}