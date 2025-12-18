@extends('layouts.admin')

@section('content')
<div class="page-header mb-4">
    <h1 class="h2 fw-bold text-dark"><i class="fas fa-apple-alt me-2"></i>Healthy Food</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Healthy Food</li>
        </ol>
    </nav>
</div>
<div class="row g-4">
    {{-- Create Form --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="fas fa-plus-circle me-2"></i>Add New Healthy Food
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Healthy Food Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="e.g. Almonds" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-save me-2"></i>Save</button>
                </form>
            </div>
        </div>
    </div>

    {{-- List Table --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-list me-2"></i>All Healthy Food</span>
                <span class="badge bg-light text-dark">{{ $foods->count() }}</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 15%;">Image</th>
                        <th style="width: 25%">Name</th>
                        <th style="width: 25%">Category</th>
                        <th style="width: 15%">Calories</th>
                        <th style="width: 10%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($foods as $food)
                        <tr>
                            <td class="p-0">
                                <img src="{{ asset('storage/'.$food->image) }}" 
                                alt="{{ $food->name }}" 
                                style="width: 100%; height: 100%; object-fit: cover;">
                            </td>
                            <td>{{ $food->name }}</td>
                            <td>{{ $food->category->name ?? '-' }}</td>
                            <td>{{ $food->calories }} kcal</td>
                            <td class="text-center">
                                <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this Food?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fs-1 mb-3 d-block"></i>
                                No categories found. Add one on the left.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{ $foods->links() }}
@endsection