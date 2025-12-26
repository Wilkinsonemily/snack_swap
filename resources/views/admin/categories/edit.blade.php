@extends('admin.layout')

@section('content')
<div class="container">
  <h1>Edit Category</h1>

  <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
    </div>

    <button class="btn btn-primary">Save</button>
  </form>
</div>
@endsection
