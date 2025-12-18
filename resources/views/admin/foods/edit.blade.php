@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Food: <span class="text-primary">{{ $food->name }}</span></h2>
        <a href="{{ route('admin.foods.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            
            <form action="{{ route('admin.foods.update', $food->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Food Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $food->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $food->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Health Reason (Why choose this?)</label>
                            <textarea name="health_reason" class="form-control" rows="3">{{ old('health_reason', $food->health_reason) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Image</label>
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $food->image) }}" class="img-thumbnail" style="max-height: 200px; width: 100%; object-fit: cover;">
                            </div>
                            <label class="form-label small text-muted">Change Image (Optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3">Nutrition Facts (per 100g/serving)</h5>
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label small">Calories</label>
                        <input type="number" step="0.01" name="calories" class="form-control" value="{{ $food->calories }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Sugar (g)</label>
                        <input type="number" step="0.01" name="sugar" class="form-control" value="{{ $food->sugar }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Fat (g)</label>
                        <input type="number" step="0.01" name="fat" class="form-control" value="{{ $food->fat }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Sodium (mg)</label>
                        <input type="number" step="1" name="sodium" class="form-control" value="{{ $food->sodium }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Protein (g)</label>
                        <input type="number" step="0.01" name="protein" class="form-control" value="{{ $food->protein }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Fiber (g)</label>
                        <input type="number" step="0.01" name="fiber" class="form-control" value="{{ $food->fiber }}">
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="is_vegan" class="form-check-input" id="vegan" {{ $food->is_vegan ? 'checked' : '' }}>
                        <label class="form-check-label" for="vegan">🌱 Vegan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="is_gluten_free" class="form-check-input" id="gluten" {{ $food->is_gluten_free ? 'checked' : '' }}>
                        <label class="form-check-label" for="gluten">🌾 Gluten Free</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">Update Food Data</button>
            </form>
        </div>
    </div>
</div>
@endsection