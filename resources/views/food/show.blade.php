@extends('layouts.app')
@section('title', $food['name'])

@section('content')
<div class="container py-4">
  <div class="row g-4 justify-content-center">
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <img class="img-fluid rounded" style="max-height:420px;object-fit:cover"
             src="{{ $food['image'] ?? 'https://picsum.photos/seed/food/600/600' }}" alt="{{ $food['name'] }}">
      </div>
    </div>

    <div class="col-md-6">
      <h2 class="fw-bold">{{ $food['name'] }}</h2>
      <div class="text-muted mb-2">{{ $food['brand'] ?? '-' }}</div>
      @if(!empty($food['category']))
        <span class="badge text-bg-info text-dark mb-3">{{ $food['category'] }}</span>
      @endif

      <div class="card shadow-sm p-4 mb-4">
        <h5 class="fw-semibold mb-3">Nutrition (per 100g)</h5>
        <div class="d-flex justify-content-between mb-2">
          <span>Calories</span><span class="fw-bold">{{ $food['calories'] ?? '-' }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span>Sugar</span><span class="fw-bold">{{ $food['sugar'] ?? '-' }} g</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span>Fat</span><span class="fw-bold">{{ $food['fat'] ?? '-' }} g</span>
        </div>
        <div class="d-flex justify-content-between">
          <span>Sodium</span><span class="fw-bold">{{ $food['sodium'] ?? '-' }} mg</span>
        </div>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('swap.result', $food['id']) }}" class="btn btn-dark px-4">Find Healthy Swap</a>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">Back</a>
      </div>
    </div>
  </div>
</div>
@endsection