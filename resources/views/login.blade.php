@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="logo">📝 ContentGen</div>
    <h2 class="mb-4 text-center">Brand Login</h2>

    <div class="mb-3 text-center">
        <button id="web-toggle" class="btn btn-link" type="button">Web Login</button> |
        <button id="api-toggle" class="btn btn-link" type="button">API Login</button>
    </div>

    {{-- Web Login Form --}}
    <form id="web-login-form" method="POST" action="/login" autocomplete="off">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Web Login</button>
    </form>

    {{-- API Login Form --}}
    <form id="api-login-form" style="display:none;" autocomplete="off">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" id="api-email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" id="api-password" class="form-control" required>
        </div>
        <button type="button" id="api-login-btn" class="btn btn-secondary w-100">API Login</button>
        <div id="api-login-result" class="mt-2 text-center text-success"></div>
    </form>

    <div class="mt-3 text-center">
        Don't have an account? <a href="/register">Register here</a>
    </div>
</div>

<script>
document.getElementById('web-toggle').onclick = function() {
    document.getElementById('web-login-form').style.display = '';
    document.getElementById('api-login-form').style.display = 'none';
};
document.getElementById('api-toggle').onclick = function() {
    document.getElementById('web-login-form').style.display = 'none';
    document.getElementById('api-login-form').style.display = '';
};
document.getElementById('api-login-btn').onclick = async function(e) {
    e.preventDefault();
    const email = document.getElementById('api-email').value;
    const password = document.getElementById('api-password').value;
    const resultDiv = document.getElementById('api-login-result');
    resultDiv.textContent = 'Logging in...';
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
            body: JSON.stringify({email, password})
        });
        const data = await response.json();
        if (response.ok) {
            resultDiv.textContent = 'API Token: ' + data.token;
        } else {
            resultDiv.textContent = data.message || 'Login failed';
        }
    } catch (err) {
        resultDiv.textContent = 'Error logging in';
    }
};
</script>
@endsection