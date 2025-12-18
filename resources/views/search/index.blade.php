@extends('layouts.app')
@section('title','Search')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">
        Search Results
        @if($query) <small class="text-muted">for “{{ $query }}”</small>@endif
    </h3>

    <form method="GET" action="{{ route('search') }}" class="d-flex gap-2">
        <input type="text" name="query" class="form-control" value="{{ $query }}" placeholder="Search…">
        <select name="region" class="form-select">
        <option value="global" @selected($region==='global')>Global</option>
        <option value="id" @selected($region==='id')>Indonesia</option>
        </select>
        <button class="btn btn-dark">Search</button>
    </form>
    </div>

    @if($error)
    <div class="alert alert-warning">
        Some external results couldn’t be loaded ({{ $error }}). Showing what we have.
    </div>
    @endif

    @if(empty($products))
    <div class="alert alert-secondary">No results.</div>
    @else
    <div class="row g-3">
        @foreach($products as $p)
        @php
    
            $img = $p['image'] ?? null;
            $img = $img && preg_match('~^https?://~', $img)
                ? $img
                : ($img ? asset($img) : 'https://picsum.photos/seed/snackswap/400/260');

            $cat = $p['category'] ?? null;
            if ($cat) {
            $cat = str_contains($cat, ':') ? ucwords(str_replace('-', ' ', explode(':', $cat, 2)[1])) 
                                            : ucwords(str_replace('-', ' ', $cat));
            }
        @endphp

        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card shadow-sm border-0 search-card">
            <img src="{{ $img }}" class="search-card-img" alt="{{ $p['name'] }}">

            <div class="card-body">
                <h6 class="fw-semibold mb-1 text-truncate" title="{{ $p['name'] }}">{{ $p['name'] }}</h6>
                <div class="small text-muted mb-2 text-truncate" title="{{ $p['brand'] ?? '-' }}">
                {{ $p['brand'] ?? '-' }}
                </div>

                @if($cat)
                <span class="badge text-bg-info text-dark mb-3">{{ $cat }}</span>
                @endif

                <div class="mt-auto d-flex gap-2">
                <a href="{{ route('food.show', $p['id']) }}" class="btn btn-outline-secondary btn-sm w-100">
                    Details
                </a>
                <a href="{{ route('swap.result', ['barcode' => $p['id']]) }}" class="btn btn-dark btn-sm w-100">
                    Swap
                </a>
                </div>
            </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-3 d-flex justify-content-end">
        @if($next)
        <a class="btn btn-outline-primary btn-sm" href="{{ $next }}">Next »</a>
        @endif
    </div>
    @endif
@endsection