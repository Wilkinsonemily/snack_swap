@extends('layouts.app')
@section('title','Healthy Catalog')

@section('content')
<h3 class="fw-bold mb-4">Healthy Food Catalog</h3>

<form class="row g-3 mb-4">
  <div class="col-md-4">
    <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="Search healthy snacks...">
  </div>

  <div class="col-md-3">
    <select name="badge" class="form-select">
      <option value="">All</option>
      <option value="low_sugar" @selected($badge==='low_sugar')>Low Sugar</option>
      <option value="high_protein" @selected($badge==='high_protein')>High Protein</option>
      <option value="high_fiber" @selected($badge==='high_fiber')>High Fiber</option>
      <option value="vegan" @selected($badge==='vegan')>Vegan</option>
      <option value="gluten_free" @selected($badge==='gluten_free')>Gluten Free</option>
    </select>
  </div>

  <div class="col-md-3">
    <select name="sort" class="form-select">
      <option value="name_asc" @selected($sort==='name_asc')>Name A–Z</option>
      <option value="cal_asc" @selected($sort==='cal_asc')>Calories Low → High</option>
      <option value="cal_desc" @selected($sort==='cal_desc')>Calories High → Low</option>
    </select>
  </div>

  <div class="col-md-2">
    <button class="btn btn-dark w-100">Filter</button>
  </div>
</form>
</div>

@if($foods->isEmpty())
  <div class="alert alert-secondary">No healthy items yet. Run <code>php artisan healthy:sync snack</code>.</div>
@else
  <div class="row g-3">
    @foreach($foods as $f)
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="card h-100 shadow-sm">
          <div class="ratio ratio-4x3">
            <img src="{{ asset('storage/'.$f->image) ?: 'https://picsum.photos/seed/healthy/600/450' }}" alt="{{ $f->name }}" class="object-fit-contain p-2">
          </div>
          <div class="card-body d-flex flex-column">
            <h6 class="fw-semibold mb-1">{{ $f->name }}</h6>
            <div class="small text-muted mb-2">{{ $f->brand ?: '-' }}</div>

            <div class="d-flex flex-wrap gap-1 small mb-2">
              @if(!is_null($f->calories)) <span class="badge text-bg-light">⚡ {{ (int)$f->calories }} kcal</span> @endif
              @if(!is_null($f->protein))  <span class="badge text-bg-success">🧬 {{ $f->protein }}g protein</span> @endif
              @if(!is_null($f->fiber))    <span class="badge text-bg-info">🌾 {{ $f->fiber }}g fiber</span> @endif
              @if(!is_null($f->sugar))    <span class="badge text-bg-warning-subtle">🍭 {{ $f->sugar }}g sugar</span> @endif
              @if(!is_null($f->sodium))   <span class="badge text-bg-danger-subtle">🧂 {{ $f->sodium }}mg sodium</span> @endif
            </div>

            <div class="mt-auto">
              <a href="{{ route('healthy.show',$f->id) }}" class="btn btn-outline-secondary w-100">Details</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-3">
    {{ $foods->links() }}
  </div>
@endif
@endsection