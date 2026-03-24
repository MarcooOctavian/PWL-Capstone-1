@extends('layouts.admin')

@section('title', 'Add Schedule')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">New Schedule Details</h3>
      </div>
      <form action="{{ route('schedules.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label>Event</label>
            <select name="event_id" class="form-control" required>
              <option value="">-- Select Event --</option>
              @foreach($events as $event)
                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Location (Venue/Stage)</label>
            <select name="location_id" class="form-control" required>
              <option value="">-- Select Location --</option>
              @foreach($locations as $location)
                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->venue_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row">
            <div class="col-6">
              <label>Start Time</label>
              <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
            </div>
            <div class="col-6">
              <label>End Time</label>
              <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('schedules.index') }}" class="btn btn-default mr-2">Cancel</a>
          <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Save Schedule</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
