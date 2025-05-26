<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatGptService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostGenerationController extends Controller
{
    // Handles content generation requests
    public function generate(Request $request, ChatGptService $chatGpt): JsonResponse
    {
        // Validate the incoming request to ensure 'topic' is present and valid
        $request->validate(['topic' => 'required|string|max:255']);

        try {
            // Generate content options using the ChatGptService
            $options = $chatGpt->generateContent($request->topic);

            // Save the generation request to the database for the authenticated user
            $request->user()->generationRequests()->create([
                'topic' => $request->topic
            ]);

            // Return the generated options as a JSON response
            return response()->json(['options' => $options]);
        } catch (\Exception $e) {
            // If any error occurs, return a JSON error response with the exception message
            return response()->json([
                'error' => 'Content generation failed lol',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}