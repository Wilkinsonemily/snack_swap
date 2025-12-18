<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login – SnackSwap Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS (CDN ONLY) -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    crossorigin="anonymous"
  >

  <!-- Font Awesome (CDN ONLY) -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    rel="stylesheet"
  >

  <!-- INLINE LOGIN CSS -->
  <style>
    body.login-page {
      min-height: 100vh;
      background: radial-gradient(
          1200px 600px at 120% -20%,
          rgba(16,185,129,.25),
          transparent 60%
        ),
        radial-gradient(
          800px 400px at -10% 10%,
          rgba(14,165,233,.25),
          transparent 60%
        ),
        linear-gradient(135deg, #1f2937, #111827);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Segoe UI", system-ui, sans-serif;
    }

    .login-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .login-card {
      width: 100%;
      max-width: 450px;
      background: #ffffff;
      border-radius: 18px;
      box-shadow: 0 25px 60px rgba(0,0,0,.35);
      overflow: hidden;
      animation: slideUp .5s ease;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .login-header {
      background: linear-gradient(135deg, #1f2937, #111827);
      color: #fff;
      padding: 2.2rem;
      text-align: center;
    }

    .btn-login {
      background: linear-gradient(135deg, #1f2937, #111827);
      border: none;
      transition: .3s;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0,0,0,.25);
    }

    .input-group-text {
      background: #fff;
    }

    .back-link a {
      color: rgba(255,255,255,.85);
      transition: .2s;
    }

    .back-link a:hover {
      color: #ffffff;
      text-decoration: underline;
    }
  </style>
</head>

<body class="login-page">

<div class="login-wrapper">

  <!-- LOGIN CARD -->
  <div class="login-card">

    <!-- HEADER -->
    <div class="login-header">
      <i class="fas fa-shield-alt fa-3x mb-2"></i>
      <h3 class="fw-bold mb-1">Admin Login</h3>
      <small class="text-white-50">Enter credentials to access dashboard</small>
    </div>

    <!-- BODY -->
    <div class="p-4 p-md-5">

      @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-start mb-4">
          <i class="fas fa-exclamation-circle me-2 mt-1"></i>
          <ul class="mb-0 ps-3 small">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ url('/login') }}">
        @csrf

        <!-- EMAIL -->
        <div class="mb-4">
          <label class="form-label fw-bold small text-secondary">
            EMAIL ADDRESS
          </label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-envelope text-secondary"></i>
            </span>
            <input
              type="email"
              name="email"
              class="form-control"
              placeholder="name@example.com"
              value="{{ old('email') }}"
              required
              autofocus
            >
          </div>
        </div>

        <!-- PASSWORD -->
        <div class="mb-4">
          <label class="form-label fw-bold small text-secondary">
            PASSWORD
          </label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-lock text-secondary"></i>
            </span>
            <input
              type="password"
              id="password"
              name="password"
              class="form-control"
              placeholder="Enter password"
              required
            >
            <button
              class="btn btn-outline-secondary"
              type="button"
              onclick="togglePassword()"
            >
              <i class="fas fa-eye" id="toggleIcon"></i>
            </button>
          </div>
        </div>

        <!-- SUBMIT -->
        <button type="submit"
                class="btn btn-login text-white w-100 py-2 fw-bold">
          Login to Dashboard
          <i class="fas fa-arrow-right ms-2"></i>
        </button>
      </form>

    </div>
  </div>

  <!-- BACK TO WEBSITE -->
  <div class="back-link mt-4 text-center">
    <a href="{{ url('/') }}" class="text-decoration-none fw-semibold">
      <i class="fas fa-arrow-left me-1"></i> Back to Website
    </a>
  </div>

</div>

<!-- Bootstrap JS (CDN ONLY) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('toggleIcon');

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }
</script>

</body>
</html>
