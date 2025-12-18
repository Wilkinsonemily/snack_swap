@extends('layouts.app')
@section('title','Swap')

@section('content')
<div class="container py-5">
    <h1 class="text-center fw-bold mb-5">The Swap Result</h1>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow h-100 border-danger">
                <div class="card-header bg-danger text-white text-center">Original</div>
                <img src="{{ $unhealthyFood['image'] }}" class="card-img-top p-4" style="height: 250px; object-fit: contain;">
                <div class="card-body text-center">
                    <h5>{{ $unhealthyFood['name'] }}</h5>
                    <p class="text-danger fw-bold">{{ $unhealthyFood['calories'] }} kcal</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 text-center align-self-center">
            @if($primarySwap)
                <div class="display-4 fw-bold text-success mb-3">VS</div>
                <div class="card p-3 shadow-sm">
                    <h6>Calories Saved:</h6>
                    <h3 class="{{ $comparison['calories_saved'] > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $comparison['calories_saved'] > 0 ? '-' : '+' }}{{ abs($comparison['calories_saved']) }}
                    </h3>
                </div>
            @else
                <div class="alert alert-warning">No swap found for this category yet.</div>
            @endif
        </div>

        <div class="col-md-4">
            @if($primarySwap)
            <div class="card shadow h-100 border-success">
                <div class="card-header bg-success text-white text-center">Better Choice</div>
                {{-- Handle Image Storage Link --}}
                <img src="{{ asset('storage/' . $primarySwap->image) }}" class="card-img-top p-4" style="height: 250px; object-fit: contain;">
                <div class="card-body text-center">
                    <h5>{{ $primarySwap->name }}</h5>
                    <p class="text-success fw-bold">{{ $primarySwap->calories }} kcal</p>
                    <p class="small text-muted fst-italic">"{{ $primarySwap->health_reason }}"</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection