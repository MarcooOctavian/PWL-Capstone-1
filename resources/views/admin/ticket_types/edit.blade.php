@extends('layouts.admin')

@section('title', 'Edit Ticket Type')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Edit Ticket Type</h3>
            </div>

            <form action="{{ route('ticket-types.update', $ticketType->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Event</label>
                        <select name="event_id" class="form-control" readonly>
                            @foreach($events as $event)
                                @if($ticketType->event_id == $event->id)
                                    <option value="{{ $event->id }}" selected>{{ $event->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Schedule</label>
                        <select name="schedule_id" class="form-control" required>
                            <option value="">-- Dropdown / Select Schedule --</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ $ticketType->schedule_id == $schedule->id ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('d M Y, H:i') }}
                                    - {{ \Carbon\Carbon::parse($schedule->end_time)->translatedFormat('H:i') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $ticketType->name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control" value="{{ $ticketType->price }}" required>
                    </div>

                    <div class="form-group">
                        <label>Total Stock (Quota)</label>
                        <input type="number" name="stock" class="form-control" value="{{ $ticketType->stock }}" required>
                    </div>

                    <div class="form-group">
                        <label>Maximum Purchase</label>
                        <input type="number" name="max_purchase" class="form-control" value="{{ $ticketType->max_purchase }}" required>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('ticket-types.manage',$ticketType->event_id ?? 0) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning">Update Ticket Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
