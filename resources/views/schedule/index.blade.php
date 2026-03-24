@extends('layouts.admin')

@section('title', 'All Schedules')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card card-warning card-outline">
      <div class="card-header">
        <h3 class="card-title">Event Schedules</h3>
        <div class="card-tools">
          <a href="{{ route('schedules.create') }}" class="btn btn-sm btn-dark">
            <i class="fas fa-plus"></i> Add Schedule
          </a>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>ID</th>
              <th>Event Name</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Location</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($schedules as $schedule)
            <tr>
              <td>{{ $schedule->id }}</td>
              <td>{{ $schedule->event->title ?? 'N/A' }}</td>
              <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
              <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
              <td>{{ $schedule->location->venue_name ?? 'N/A' }}</td>
              <td>
                <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
            @if($schedules->isEmpty())
            <tr>
              <td colspan="6" class="text-center">No schedules found. Create an event and location first!</td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
