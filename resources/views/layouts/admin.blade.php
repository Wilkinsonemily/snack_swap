<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin SnackSwap</title>

    {{-- Bootstrap CSS (CDN) --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    {{-- Font Awesome (CDN) --}}
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        rel="stylesheet"
    >

    {{-- Admin Custom CSS (LOCAL – ini aman) --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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

        {{-- NAVIGATION --}}
        <ul class="nav nav-pills flex-column mb-auto">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'icon' => 'fas fa-home', 'label' => 'Dashboard', 'pattern' => 'admin'],
                    ['route' => 'admin.categories.index', 'icon' => 'fas fa-layer-group', 'label' => 'Categories', 'pattern' => 'admin/categories*'],
                    ['route' => 'admin.foods.index', 'icon' => 'fas fa-apple-alt', 'label' => 'Healthy Foods', 'pattern' => 'admin/foods*'],
                    ['route' => 'admin.rules.index', 'icon' => 'fas fa-exchange-alt', 'label' => 'Swap Rules', 'pattern' => 'admin/rules*'],
                ];
            @endphp

            @foreach($navItems as $item)
                <li class="nav-item">
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ request()->is($item['pattern']) ? 'active' : '' }}">
                        <i class="{{ $item['icon'] }} me-2"></i>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- SIDEBAR FOOTER --}}
        <div class="sidebar-footer mt-auto">

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="nav-link w-100 text-start border-0 bg-transparent d-flex align-items-center">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </button>
            </form>

            <a href="/" class="d-flex align-items-center mt-2">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Website
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content flex-grow-1 p-4">

        {{-- FLASH MESSAGE --}}
        @foreach(['success', 'error'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg === 'success' ? 'success' : 'danger' }}">
                    <i class="fas fa-{{ $msg === 'success' ? 'check' : 'exclamation' }}-circle me-2"></i>
                    {{ session($msg) }}
                </div>
            @endif
        @endforeach

        @yield('content')
    </main>
</div>

{{-- Bootstrap JS Bundle (CDN) --}}
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-toggle');

    function toggleSidebar() {
        sidebar.classList.toggle('show');
    }

    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !toggle.contains(e.target)) {
            sidebar.classList.remove('show');
        }
    });
</script>

</body>
</html>
