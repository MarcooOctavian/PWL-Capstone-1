@extends('layouts.admin')

@section('title', 'Add Ticket Type')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create New Ticket Type</h3>
            </div>

            <form action="{{ route('ticket-types.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Event</label>
                        <select name="event_id" class="form-control" readonly>
                            @foreach($events as $event)
                                @if(isset($selectedEvent) && $selectedEvent == $event->id)
                                    <option value="{{ $event->id }}" selected>
                                        {{ $event->title }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Schedule</label>
                        <select name="schedule_id" class="form-control" required>
                            <option value="">-- Dropdown / Select Schedule --</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('d M Y, H:i') }}
                                    - {{ \Carbon\Carbon::parse($schedule->end_time)->translatedFormat('H:i') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Name (e.g., VIP, Regular)</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control" placeholder="0" required>
                    </div>

                    <div class="form-group">
                        <label>Total Stock (Quota for this type)</label>
                        <input type="number" name="stock" class="form-control" placeholder="100" required>
                    </div>

                    <div class="form-group">
                        <label>Maximum Purchase per checkout</label>
                        <input type="number" name="max_purchase" class="form-control" placeholder="2" value="2" required>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('ticket-types.manage',$selectedEvent) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Ticket Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
