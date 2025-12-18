<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin SnackSwap</title>

    {{-- Bootstrap CSS (CDN ONLY) --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        crossorigin="anonymous"
    >

    {{-- Font Awesome (CDN ONLY) --}}
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        rel="stylesheet"
    >

    {{-- INLINE ADMIN CSS --}}
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .admin-wrapper {
            width: 100%;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            background: linear-gradient(180deg, #2c3e50, #34495e);
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform .3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            background: rgba(0,0,0,.2);
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .sidebar hr {
            border-color: rgba(255,255,255,.1);
            margin: 0;
        }

        .sidebar .nav-link,
        .sidebar-footer a {
            color: rgba(255,255,255,.7);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: .3s;
        }

        .sidebar .nav-link i,
        .sidebar-footer i {
            margin-right: .75rem;
            width: 20px;
        }

        .sidebar .nav-link:hover,
        .sidebar-footer a:hover {
            background: rgba(255,255,255,.05);
            color: #fff;
            border-left-color: #3498db;
        }

        .sidebar .nav-link.active {
            background: rgba(52,152,219,.2);
            color: #fff;
            border-left-color: #3498db;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1.5rem;
            background: rgba(0,0,0,.2);
            border-top: 1px solid rgba(255,255,255,.1);
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* MOBILE */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1100;
            background: #2c3e50;
            color: #fff;
            border: none;
            padding: .75rem 1rem;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: block; }
        }

        /* UI POLISH */
        .alert {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }

        .stat-card {
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
            transition: transform .3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<button class="mobile-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<div class="d-flex admin-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/" class="text-white text-decoration-none d-flex align-items-center gap-2">
                <i class="fas fa-shield-alt"></i>
                <span class="fs-4 fw-semibold">Admin</span>
            </a>
        </div>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            @foreach([
                ['route'=>'admin.dashboard','icon'=>'fa-home','label'=>'Dashboard','pattern'=>'admin'],
                ['route'=>'admin.categories.index','icon'=>'fa-layer-group','label'=>'Categories','pattern'=>'admin/categories*'],
                ['route'=>'admin.foods.index','icon'=>'fa-apple-alt','label'=>'Healthy Foods','pattern'=>'admin/foods*'],
                ['route'=>'admin.rules.index','icon'=>'fa-exchange-alt','label'=>'Swap Rules','pattern'=>'admin/rules*'],
            ] as $item)
            <li class="nav-item">
                <a href="{{ route($item['route']) }}"
                   class="nav-link {{ request()->is($item['pattern']) ? 'active' : '' }}">
                    <i class="fas {{ $item['icon'] }}"></i>
                    {{ $item['label'] }}
                </a>
            </li>
            @endforeach
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="nav-link w-100 text-start border-0 bg-transparent">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>

            <a href="/" class="mt-2 d-flex align-items-center">
                <i class="fas fa-arrow-left"></i> Back to Website
            </a>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main-content flex-grow-1">
        @yield('content')
    </main>
</div>

{{-- Bootstrap JS (CDN ONLY) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
</script>

</body>
</html>
