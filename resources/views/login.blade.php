@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="logo">📝 ContentGen</div>
    
    <h2 class="mb-4 text-center">Brand Login</h2>
    
    <form id="loginForm" autocomplete="off">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
        
        <div class="mt-3 text-center">
            Don't have an account? <a href="/register">Register here</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    // Prevent double submission
    if (window.loginSubmitting) return;
    window.loginSubmitting = true;

    const form = event.target;
    const email = form.email.value;
    const password = form.password.value;

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();

        if (!response.ok) throw data;

        // Store token and redirect
        localStorage.setItem('auth_token', data.token);
        window.location.href = '/dashboard';
    } catch (error) {
        alert(error.message || error.email?.[0] || 'Login failed');
    } finally {
        window.loginSubmitting = false;
    }
});
</script>
@endsection