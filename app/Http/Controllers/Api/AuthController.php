<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'brand_name' => 'required|string|max:255',
            'brand_description' => 'required|string',
            'website' => 'nullable|url'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'brand_name' => $request->brand_name,
            'brand_description' => $request->brand_description,
            'website' => $request->website
        ]);

        // If the request expects JSON (API/AJAX), return token
        if ($request->expectsJson()) {
            return response()->json([
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user' => $user
            ], 201);
        }

        // Otherwise, log in the user and redirect to dashboard for Blade forms
        Auth::login($user);
        $request->session()->regenerate();
        return redirect('/dashboard')->with('success', 'Registration successful! Welcome!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->expectsJson()) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // If the request expects JSON (API/AJAX), return token
        if ($request->expectsJson()) {
            return response()->json([
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user' => $user
            ]);
        }

        // Otherwise, log in the user and redirect to dashboard for Blade forms
        Auth::login($user);
        $request->session()->regenerate();
        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        if ($request->expectsJson()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully');
    }
}