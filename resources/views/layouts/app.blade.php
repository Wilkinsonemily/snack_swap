<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','SnackSwap')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap CSS (CDN) --}}
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  >

  {{-- Bootstrap Icons --}}
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
  >

  {{-- INLINE APP CSS --}}
  <style>
    :root{
      --ss-sky:#0ea5e9;
      --ss-emerald:#10b981;
    }

    .bg-body{
      background:
        radial-gradient(1200px 600px at 120% -20%, #10b98120, transparent 60%),
        radial-gradient(800px 400px at -10% 10%, #0ea5e920, transparent 60%),
        linear-gradient(180deg, #0b122033, transparent 40%);
      min-height:100vh;
    }

    .glass-nav{
      background: linear-gradient(90deg, rgba(14,165,233,.55), rgba(16,185,129,.55));
      backdrop-filter: blur(6px);
      border-bottom: 1px solid rgba(255,255,255,.15);
    }

    .footer-grad{
      background: linear-gradient(90deg, rgba(14,165,233,.85), rgba(16,185,129,.85));
    }

    .hero{
      background: linear-gradient(120deg, #0ea5e930, #10b98130);
      border: 1px solid rgba(255,255,255,.25);
      backdrop-filter: blur(6px);
      border-radius: 1rem;
    }

    .badge-chip{
      background: linear-gradient(90deg, #0ea5e9, #10b981);
      color: #fff;
    }

    .section-gap{ margin:4rem 0; }

    .glass-card{
      border-radius: 18px;
      background: rgba(255,255,255,.55);
      box-shadow: 0 20px 45px rgba(16,24,40,.08);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,.35);
    }

    .gradient-text{
      background: linear-gradient(90deg, #0ea5e9, #10b981);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .pill{
      display:inline-flex;
      align-items:center;
      gap:.5rem;
      padding:.5rem .85rem;
      border-radius:999px;
      background: linear-gradient(90deg, #0ea5e922, #10b98122);
      border: 1px solid rgba(14,165,233,.25);
      font-weight:600;
      font-size:.9rem;
    }

    .feature-emoji{
      width:56px;
      height:56px;
      border-radius:14px;
      display:grid;
      place-items:center;
      font-size:26px;
      background: linear-gradient(135deg, #fff, #f1f5f9);
      border:1px solid rgba(2,6,23,.05);
      box-shadow: 0 10px 30px rgba(2,6,23,.08);
    }

    .card-hover:hover{
      transform: translateY(-3px);
      transition:.25s ease;
    }

    .soft-divider{
      height:1px;
      width:100%;
      background: linear-gradient(90deg, transparent, rgba(2,6,23,.08), transparent);
    }

    .cta-wrap{
      background: linear-gradient(120deg, #0ea5e922, #10b98122);
      border: 1px dashed rgba(14,165,233,.45);
      border-radius: 18px;
    }

    .badge-source{
      font-size:.75rem;
      background: linear-gradient(90deg, #0ea5e9, #10b981);
      color:#fff;
      border:none;
    }

    .search-card{
      height:100%;
      display:flex;
      flex-direction:column;
    }

    .search-card-img{
      width:100%;
      height:220px;
      object-fit:cover;
      border-radius:8px;
      background:#f3f4f6;
    }

    .search-card .card-body{
      flex:1;
      display:flex;
      flex-direction:column;
    }
  </style>
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
        <ul class="navbar-nav ms-auto gap-1">

          <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('home')) active @endif" href="{{ route('home') }}">
              <i class="bi bi-house-door"></i> Home
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('search')) active @endif" href="{{ route('search') }}">
              <i class="bi bi-search"></i> Search
            </a>
          </li>

          @if(Route::has('healthy.index'))
          <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('healthy.*')) active @endif" href="{{ route('healthy.index') }}">
              <i class="bi bi-heart-pulse"></i> Healthy Catalogue
            </a>
          </li>
          @endif
        <li class="nav-item">
            <a class="nav-link" href="{{ route('about') }}">About</a>
        </li>

        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-5 flex-grow-1">
    @yield('content')
  </main>

  <footer class="py-5 text-center text-white-50 footer-grad mt-auto">
    <div class="container small">
      <div>&copy; {{ date('Y') }} SnackSwap — Eat smarter, not lesser.</div>
    </div>
  </footer>

  {{-- Bootstrap JS --}}
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"
  ></script>
</body>
</html>
