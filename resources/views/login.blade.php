<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SnackSwap Admin</title>
    
    {{-- Assets --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="login-page">

    <div class="container" style="max-width: 450px;">
        
        <div class="card login-card border-0 shadow-lg">
            
            <div class="login-header">
                <i class="fas fa-shield-alt fa-3x mb-2"></i>
                <h3 class="fw-bold mb-1">Admin Login</h3>
                <p class="small text-white-50 mb-0">Enter credentials to access dashboard</p>
            </div>

            <div class="card-body p-4 p-md-5">
                
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div class="small">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">EMAIL ADDRESS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-secondary"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">PASSWORD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-secondary"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login text-white w-100 py-2 fw-bold mb-3">
                        Login to Dashboard <i class="fas fa-arrow-right ms-2"></i>
                    </button>

                </form>
            </div>
        </div>

        <div class="back-link text-center mt-4">
            <a href="{{ url('/') }}" class="text-white text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i> Back to Website
            </a>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>