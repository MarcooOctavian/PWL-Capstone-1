@extends('layouts.admin')

@section('title', 'Ticket Types')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Session Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card card-primary card-outline">
                <!-- Event Title & Add Ticket Type Button Conditional -->
                <div class="card-header">
                    <h3 class="card-title">Ticket Types Management - {{ $event->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('ticket-types.index') }}" class="btn btn-sm btn-secondary">
                            Back
                        </a>
                        <!-- Add Ticket Type Access Conditional -->
                        @if(Auth::check() && Auth::user()->role != 3 && strtolower($event->status) !== 'completed')
                        <a href="{{ route('ticket-types.create') }}?event_id={{ $event->id }}"
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add Ticket Type
                        </a>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Schedules Iteration -->
                    @foreach($schedules as $schedule)
                        <div class="mb-4">
                            <h5>
                                <i class="fas fa-calendar-alt text-primary"></i> 
                                {{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('d M Y, H:i') }}
                                - {{ \Carbon\Carbon::parse($schedule->end_time)->translatedFormat('H:i') }}
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-nowrap mt-2">
                                    <thead class="bg-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Max Purchase</th>
                                        @if(Auth::check() && Auth::user()->role != 3 && strtolower($event->status) !== 'completed')
                                        <th>Actions</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $scheduleTickets = $ticketTypes->where('schedule_id', $schedule->id);
                                    @endphp
                                    <!-- Schedule Tickets Iteration -->
                                    @foreach($scheduleTickets as $tt)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $tt->name }}</td>
                                            <td>Rp {{ number_format($tt->price, 0, ',', '.') }}</td>
                                            <td>{{ $tt->stock }}</td>
                                            <td>{{ $tt->max_purchase }}</td>
                                            @if(Auth::check() && Auth::user()->role != 3 && strtolower($event->status) !== 'completed')
                                            <td>
                                                <a href="{{ route('ticket-types.edit', $tt->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                <form action="{{ route('ticket-types.destroy', $tt->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach

                                    @if($scheduleTickets->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No ticket types found for this schedule</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                    @if($schedules->isEmpty())
                        <div class="alert alert-warning text-center">
                            No schedules found for this event. Please add schedules first.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
