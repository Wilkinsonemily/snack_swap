<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin SnackSwap</title>

    {{-- Assets --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<button class="mobile-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

<div class="d-flex admin-wrapper">
    {{-- Sidebar --}}
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/" class="text-white text-decoration-none">
                <i class="fas fa-shield-alt me-2"></i><span class="fs-4">Admin</span>
            </a>
        </div>
        <hr>
        
        {{-- Navigation Logic --}}
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
                <li>
                    <a href="{{ route($item['route']) }}" 
                       @class(['nav-link', 'active' => request()->is($item['pattern'])])>
                        <i class="{{ $item['icon'] }}"></i>{{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- UPDATED SIDEBAR FOOTER --}}
        <div class="sidebar-footer">
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent" 
                        style="color: rgba(255,255,255,0.7); padding: 1rem 1.5rem; display: flex; align-items: center;">
                    <i class="fas fa-sign-out-alt" style="margin-right: 0.75rem; width: 20px; font-size: 1.1rem;"></i>
                    Logout
                </button>
            </form>

            <a href="/">
                <i class="fas fa-arrow-left"></i>Back to Website
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content flex-grow-1">
        @foreach(['success', 'error'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg == 'success' ? 'success' : 'danger' }}">
                    <i class="fas fa-{{ $msg == 'success' ? 'check' : 'exclamation' }}-circle me-2"></i>{{ session($msg) }}
                </div>
            @endif
        @endforeach
        
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-toggle');
    
    function toggleSidebar() { sidebar.classList.toggle('show'); }

    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('show');
        }
    });
</script>
</body>
</html>