<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS Content Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .auth-card {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .logo {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function handleAuthForm(event, isRegister) {
            event.preventDefault();
            const form = event.target;
            const url = isRegister ? '/api/register' : '/api/login';
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: form.name?.value,
                        email: form.email.value,
                        password: form.password.value,
                        ...(isRegister && {
                            brand_name: form.brand_name.value,
                            brand_description: form.brand_description.value,
                            website: form.website.value
                        })
                    })
                });

                const data = await response.json();
                
                if (!response.ok) throw data;
                
                localStorage.setItem('auth_token', data.token);
                window.location.href = '/dashboard';
            } catch (error) {
                alert(error.message || 'Authentication failed');
            }
        }
    </script>
</body>
</html>