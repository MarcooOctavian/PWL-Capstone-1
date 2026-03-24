@extends('layouts.admin')

@section('title', 'Edit Schedule')

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Schedule</h3>
      </div>
      <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label>Event</label>
            <select name="event_id" class="form-control" required>
              @foreach($events as $event)
                <option value="{{ $event->id }}" {{ (old('event_id', $schedule->event_id) == $event->id) ? 'selected' : '' }}>{{ $event->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Location (Venue/Stage)</label>
            <select name="location_id" class="form-control" required>
              @foreach($locations as $location)
                <option value="{{ $location->id }}" {{ (old('location_id', $schedule->location_id) == $location->id) ? 'selected' : '' }}>{{ $location->venue_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row">
            <div class="col-6">
              <label>Start Time</label>
              <input type="time" name="start_time" class="form-control" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" required>
            </div>
            <div class="col-6">
              <label>End Time</label>
              <input type="time" name="end_time" class="form-control" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" required>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('schedules.index') }}" class="btn btn-default mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Schedule</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
