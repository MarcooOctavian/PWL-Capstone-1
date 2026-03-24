@extends('layouts.admin')

@section('title', 'Edit Location')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Location: {{ $location->venue_name }}</h3>
      </div>
      <form action="{{ route('locations.update', $location->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label>Venue Name</label>
            <input type="text" name="venue_name" class="form-control @error('venue_name') is-invalid @enderror" value="{{ old('venue_name', $location->venue_name) }}" required>
            @error('venue_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>City</label>
            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $location->city) }}" required>
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>Complete Address</label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $location->address) }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>Google Maps URL (Optional)</label>
            <input type="url" name="maps_url" class="form-control @error('maps_url') is-invalid @enderror" value="{{ old('maps_url', $location->maps_url) }}">
            @error('maps_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('locations.index') }}" class="btn btn-default mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Location</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
