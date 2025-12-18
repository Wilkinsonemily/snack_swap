@extends('layouts.admin')

@section('content')
<div class="page-header mb-4">
    <h1 class="h2 fw-bold text-dark">Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

{{-- 1. Statistics Cards --}}
<div class="row g-4 mb-4">
    @php
        $cards = [
            ['label' => 'Total Foods', 'val' => $totalFoods, 'icon' => 'fa-apple-alt', 'color' => 'blue', 'sub' => 'Active items'],
            ['label' => 'Categories', 'val' => $totalCategories, 'icon' => 'fa-layer-group', 'color' => 'green', 'sub' => 'Available'],
            ['label' => 'Swap Rules', 'val' => $totalRules, 'icon' => 'fa-exchange-alt', 'color' => 'yellow', 'sub' => 'Active rules']
        ];
    @endphp

    @foreach($cards as $c)
        <div class="col-md-4">
            <div class="card stat-card {{ $c['color'] }}">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="stat-label text-muted text-uppercase mb-1 small fw-bold">{{ $c['label'] }}</p>
                        <h2 class="stat-value fw-bold mb-1">{{ $c['val'] }}</h2>
                        <small class="text-muted"><i class="fas fa-check-circle me-1"></i>{{ $c['sub'] }}</small>
                    </div>
                    <div class="stat-icon"><i class="fas {{ $c['icon'] }}"></i></div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- 2. Quick Actions --}}
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
        <h5 class="card-title mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.foods.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Food</a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-success"><i class="fas fa-folder-plus me-2"></i>New Category</a>
            <a href="{{ route('admin.rules.index') }}" class="btn btn-warning"><i class="fas fa-sync me-2"></i>New Rule</a>
            <a href="{{ route('admin.foods.index') }}" class="btn btn-info"><i class="fas fa-list me-2"></i>View All</a>
        </div>
    </div>
</div>

<div class="row">
    {{-- 3. Recent Activity --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
                <div class="list-group list-group-flush">
                    @forelse($recentFoods ?? [] as $food)
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">New item added: <strong>{{ $food->name }}</strong></h6>
                                <small class="text-muted">{{ $food->created_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-muted">Category: {{ $food->category->name ?? 'None' }}</small>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle mb-2 fs-4"></i><br>No recent activity found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- 4. System Health --}}
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-server me-2"></i>System Status</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Status</span><span class="badge bg-success">Online</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Last Updated</span><span class="text-muted small">{{ now()->format('M d, Y') }}</span>
                    </li>
                    <li class="list-group-item px-0 mt-2">
                        <small class="text-muted d-block mb-1">Rule Coverage</small>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: {{ $totalFoods > 0 ? ($totalRules / $totalFoods) * 100 : 0 }}%"></div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection