<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','SnackSwap')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap LOCAL --}}
  <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
  {{-- Icons (CDN ok) --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="d-flex flex-column min-vh-100 bg-body">

  {{-- NAVBAR --}}
  <nav class="navbar navbar-expand-lg navbar-dark glass-nav">

    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('home') }}">
        <i class="bi bi-cup-straw"></i> SnackSwap
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="nav" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-1">

          {{-- Home --}}
          <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('home')) active @endif" href="{{ route('home') }}">
              <i class="bi bi-house-door"></i> Home
            </a>
          </li>

          {{-- Search --}}
          <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('search')) active @endif" href="{{ route('search') }}">
              <i class="bi bi-search"></i> Search
            </a>
          </li>

          {{-- Healthy Catalogue --}}
          @if(function_exists('route') && Route::has('healthy.index'))
          <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('healthy.*')) active @endif" href="{{ route('healthy.index') }}">
              <i class="bi bi-heart-pulse"></i> Healthy Catalogue
            </a>
          </li>
          @endif

          {{-- (Opsional) Products/Swap menu lain bisa ditambah di sini --}}
        </ul>
      </div>
    </div>
  </nav>

  {{-- FLASH --}}
  <div class="container mt-3">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
  </div>

  {{-- MAIN CONTENT --}}
  <main class="container py-5 flex-grow-1">
    @yield('content')
  </main>

  {{-- FOOTER --}}
  <footer class="py-5 text-center text-white-50 footer-grad mt-auto">
    <div class="container small">
      <div class="mb-2">
        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a>
        <span class="mx-2">•</span>
        <a href="{{ route('search') }}" class="text-white-50 text-decoration-none">Search</a>
        @if(function_exists('route') && Route::has('healthy.index'))
          <span class="mx-2">•</span>
          <a href="{{ route('healthy.index') }}" class="text-white-50 text-decoration-none">Healthy Catalogue</a>
        @endif
      </div>
      <div>&copy; {{ date('Y') }} SnackSwap — Eat smarter, not lesser.</div>
    </div>
  </footer>

  <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>