@extends('layouts.admin')

@section('content')
<div class="page-header mb-4">
    <h1 class="h2 fw-bold text-dark"><i class="fas fa-layer-group me-2"></i>Categories</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Categories</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    {{-- Create Form --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white fw-bold">
                <i class="fas fa-plus-circle me-2"></i>Add New Category
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="e.g. Soda Drinks" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text small"><i class="fas fa-info-circle me-1"></i>Slug generated automatically.</div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold"><i class="fas fa-save me-2"></i>Save</button>
                </form>
            </div>
        </div>
    </div>

    {{-- List Table --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-list me-2"></i>All Categories</span>
                <span class="badge bg-light text-dark">{{ $categories->count() }}</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 35%">Name</th>
                            <th style="width: 40%">Slug</th>
                            <th style="width: 25%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="fw-bold text-dark">{{ $category->name }}</td>
                                <td><span class="badge bg-light text-danger border font-monospace">{{ $category->slug }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
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
</div>
@endsection