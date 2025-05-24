@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="logo">📝 ContentGen</div>
    
    <h2 class="mb-4 text-center">Brand Registration</h2>
    
   <form method="POST" action="/register">
        @csrf
        <div class="mb-3">
            <label class="form-label">Your Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" minlength="8" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Brand Name</label>
            <input type="text" name="brand_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Brand Description</label>
            <textarea name="brand_description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Website (optional)</label>
            <input type="url" name="website" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
        
        <div class="mt-3 text-center">
            Already have an account? <a href="/login">Login here</a>
        </div>
    </form>
</div>
@endsection