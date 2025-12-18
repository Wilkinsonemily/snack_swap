@extends('layouts.app')
@section('title', $food->name)

@section('content')
<div class="row g-4">
  <div class="col-md-5">
    <div class="ratio ratio-4x3 bg-white rounded-3 border">
      <img src="{{ $food->image ?: 'https://picsum.photos/seed/healthy/800/600' }}" class="object-fit-contain p-3" alt="{{ $food->name }}">
    </div>
  </div>
  <div class="col-md-7">
    <h2 class="fw-bold">{{ $food->name }}</h2>
    <div class="text-muted mb-3">{{ $food->brand ?: '-' }}</div>

    <div class="row row-cols-2 row-cols-lg-3 g-2 mb-3">
      @if(!is_null($food->calories)) <div class="col"><div class="p-2 bg-white rounded border">⚡ <b>{{ (int)$food->calories }}</b> kcal</div></div>@endif
      @if(!is_null($food->protein))  <div class="col"><div class="p-2 bg-white rounded border">🧬 <b>{{ $food->protein }}</b> g protein</div></div>@endif
      @if(!is_null($food->fiber))    <div class="col"><div class="p-2 bg-white rounded border">🌾 <b>{{ $food->fiber }}</b> g fiber</div></div>@endif
      @if(!is_null($food->sugar))    <div class="col"><div class="p-2 bg-white rounded border">🍭 <b>{{ $food->sugar }}</b> g sugar</div></div>@endif
      @if(!is_null($food->fat))      <div class="col"><div class="p-2 bg-white rounded border">🥑 <b>{{ $food->fat }}</b> g fat</div></div>@endif
      @if(!is_null($food->sodium))   <div class="col"><div class="p-2 bg-white rounded border">🧂 <b>{{ $food->sodium }}</b> mg sodium</div></div>@endif
    </div>

    <div class="mb-3">
      @if($food->is_vegan)       <span class="badge text-bg-success">Vegan</span> @endif
      @if($food->is_gluten_free) <span class="badge text-bg-info">Gluten-free</span> @endif
      <span class="badge text-bg-dark">Healthy</span>
    </div>

    @if($food->health_reason)
      <div class="alert alert-success small">{{ $food->health_reason }}</div>
    @endif

    <a href="{{ route('healthy.index') }}" class="btn btn-outline-dark mt-2">← Back to Catalog</a>
  </div>
</div>
@endsection