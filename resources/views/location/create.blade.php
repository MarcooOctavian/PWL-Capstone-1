@extends('layouts.admin')

@section('title', 'Add Location')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">New Location Details</h3>
      </div>
      <form action="{{ route('locations.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label>Venue Name</label>
            <input type="text" name="venue_name" class="form-control @error('venue_name') is-invalid @enderror" value="{{ old('venue_name') }}" placeholder="e.g.: Main Stage A" required>
            @error('venue_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>City</label>
            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="e.g.: Jakarta" required>
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>Complete Address</label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Enter full address..." required>{{ old('address') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>Google Maps URL (Optional)</label>
            <input type="url" name="maps_url" class="form-control @error('maps_url') is-invalid @enderror" value="{{ old('maps_url') }}" placeholder="https://goo.gl/maps/...">
            @error('maps_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('locations.index') }}" class="btn btn-default mr-2">Cancel</a>
          <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Save Location</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
