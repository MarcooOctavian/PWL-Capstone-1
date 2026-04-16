@extends('layouts.admin')

@section('title', 'All Events')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card card-success card-outline">
      <div class="card-header">
        <h3 class="card-title">Event Listings</h3>
        <!-- Create Event Permission Conditional -->
        @if(Auth::check() && Auth::user()->role != 3)
        <div class="card-tools">
          <a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus"></i> Add New Event
          </a>
        </div>
        @endif
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Organizer</th>
              <th>Event Title</th>
              <th>Category</th>
              <th>Location</th>
              <th>Date</th>
              <th>Status</th>
              @if(Auth::check() && Auth::user()->role != 3)
              <th>Actions</th>
              @endif
            </tr>
          </thead>
          <tbody>
            <!-- Events Iteration -->
            @foreach($events as $event)
            <tr>
              <td>{{ $event->organizer->name ?? 'N/A' }}</td>
              <td>{{ $event->title }}</td>
              <td>{{ $event->category->name ?? 'N/A' }}</td>
              <td>{{ $event->location->venue_name ?? 'N/A' }}</td>
              <td>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</td>
              <td>
                @if($event->status == 'Upcoming' || $event->status == 'upcoming')
                    <span class="badge badge-primary">Upcoming</span>
                @elseif($event->status == 'Draft' || $event->status == 'draft')
                    <span class="badge badge-secondary">Draft</span>
                @else
                    <span class="badge badge-success">{{ ucfirst($event->status) }}</span>
                @endif
              </td>
              @if(Auth::check() && Auth::user()->role != 3)
              <td>
                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
              </td>
              @endif
            </tr>
            @endforeach
            @if($events->isEmpty())
                <tr>
                    <td colspan="{{ (Auth::check() && Auth::user()->role != 3) ? 7 : 6 }}" class="text-center">No events found. Create your first event to get started!</td>
                </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
