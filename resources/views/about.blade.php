@extends('layouts.app')
@section('title', 'About Us')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h2 class="fw-bold mb-3 text-center">About SnackSwap</h2>

            <p class="text-muted text-center mb-4">
                Making healthier food choices easier, one swap at a time.
            </p>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-2">🥗 What is SnackSwap?</h5>
                    <p class="mb-0">
                        <strong>SnackSwap</strong> is a web application that helps users find
                        healthier alternatives to packaged foods by scanning or searching products.
                        We analyze nutrition data and suggest smarter swaps based on calories, sugar,
                        and fat content.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-2">⚙️ How It Works</h5>
                    <ul class="mb-0">
                        <li>🔍 Search or scan a food product</li>
                        <li>📊 Fetch nutrition data from OpenFoodFacts API</li>
                        <li>🔄 Match with healthier alternatives from our database</li>
                        <li>✅ Show nutrition comparison & health score</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-2">🎯 Our Mission</h5>
                    <p class="mb-0">
                        Our mission is to empower people to make better food decisions without
                        complexity. Small swaps can make a big difference in long-term health.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-semibold mb-2">🚀 Built With</h5>
                    <ul class="mb-0">
                        <li>Laravel & PHP</li>
                        <li>MySQL (Railway)</li>
                        <li>OpenFoodFacts API</li>
                        <li>Bootstrap</li>
                    </ul>
                </div>
            </div>

            <div class="text-center mt-4 text-muted small">
                © {{ date('Y') }} SnackSwap. All rights reserved.
            </div>

        </div>
    </div>
</div>
@endsection
