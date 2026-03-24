@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">New Category Details</h3>
      </div>
      <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g.: Music Festival" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>Category Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Enter description for this category...">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('categories.index') }}" class="btn btn-default mr-2">Cancel</a>
          <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Save Category</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
