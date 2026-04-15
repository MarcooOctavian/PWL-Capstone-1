@extends('layouts.admin')

@section('title', 'All Locations')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card card-danger card-outline">
      <div class="card-header">
        <h3 class="card-title">Event Locations List</h3>
        <div class="card-tools">
          <a href="{{ route('locations.create') }}" class="btn btn-sm btn-danger">
            <i class="fas fa-plus"></i> Add Location
          </a>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Venue Name</th>
              <th>Address</th>
              <th>City</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($locations as $location)
            <tr>
              <td>{{ $location->venue_name }}</td>
              <td>{{ Str::limit($location->address, 30) }}</td>
              <td>{{ $location->city }}</td>
              <td>
                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
            @if($locations->isEmpty())
            <tr>
              <td colspan="4" class="text-center">No locations found. Add your first venue now!</td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
