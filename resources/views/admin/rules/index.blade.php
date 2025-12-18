@extends('layouts.admin')

@section('content')
<div class="page-header mb-4">
    <h1 class="h2 fw-bold text-dark"><i class="fas fa-exchange-alt me-2"></i>Swap Rules</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Rules</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-white fw-bold">
                <i class="fas fa-{{ isset($rule) ? 'edit' : 'plus-circle' }} me-1"></i>
                {{ isset($rule) ? 'Edit Rule' : 'Add New Rule' }}
            </div>
            <div class="card-body">
                <form action="{{ isset($rule) ? route('admin.rules.update', $rule->id) : route('admin.rules.store') }}" method="POST">
                    @csrf
                    @if(isset($rule)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">API Keyword</label>
                        <input type="text" name="api_keyword" class="form-control @error('api_keyword') is-invalid @enderror" 
                               value="{{ old('api_keyword', $rule->api_keyword ?? '') }}" placeholder="e.g. potato-chips" required>
                        @error('api_keyword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Map to Category</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $rule->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-warning text-white fw-bold flex-grow-1">Save Rule</button>
                        <a href="{{ route('admin.rules.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-list me-2"></i>Existing Rules</span>
                <span class="badge bg-secondary">{{ $rules->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>API Keyword</th>
                            <th></th>
                            <th>Suggest Category</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rules as $r)
                        <tr>
                            <td><span class="badge bg-secondary"><i class="fas fa-tag me-1"></i>{{ $r->api_keyword }}</span></td>
                            <td class="text-center text-muted"><i class="fas fa-arrow-right"></i></td>
                            <td><span class="badge bg-success"><i class="fas fa-leaf me-1"></i>{{ $r->category->name ?? 'N/A' }}</span></td>
                            <td class="text-end">
                                <form action="{{ route('admin.rules.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger border-0"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fs-4 mb-2 d-block"></i> No rules found.
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