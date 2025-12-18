@extends('layouts.admin')
@section('content')
<h2>Add New Healthy Food</h2>
<form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3"><label>Calories</label><input type="number" step="0.01" name="calories" class="form-control"></div>
        <div class="col-md-4 mb-3"><label>Sugar</label><input type="number" step="0.01" name="sugar" class="form-control"></div>
        <div class="col-md-4 mb-3"><label>Fat</label><input type="number" step="0.01" name="fat" class="form-control"></div>

        <div class="col-12 mb-3">
            <label>Health Reason (Why is it good?)</label>
            <textarea name="health_reason" class="form-control" rows="2"></textarea>
        </div>

        <div class="col-12 mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
    </div>
    <button class="btn btn-success">Save Food</button>
</form>
@endsection