@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Category: {{ $category->name }}</h3>
      </div>
      <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>Category Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('categories.index') }}" class="btn btn-default mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Category</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
