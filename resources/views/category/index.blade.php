@extends('layouts.admin')

@section('title', 'All Categories')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card card-info card-outline">
      <div class="card-header">
        <h3 class="card-title">Event Categories List</h3>
        <div class="card-tools">
          <a href="{{ route('categories.create') }}" class="btn btn-sm btn-info">
            <i class="fas fa-plus"></i> Add Category
          </a>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>No</th>
              <th>Category Name</th>
              <th>Description</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $category)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $category->name }}</td>
              <td>{{ $category->description ?? '-' }}</td>
              <td>
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
            @if($categories->isEmpty())
            <tr>
              <td colspan="4" class="text-center">No categories found in the database.</td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
