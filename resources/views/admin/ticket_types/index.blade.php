@extends('layouts.admin')

@section('title', 'Ticket Types')

@section('content')
    <div class="row">
        <div class="col-12">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Ticket Types Management</h3>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>{{ $event->title }}</td>
                                <td>{{ $event->date }}</td>
                                <td>{{ $event->status }}</td>
                                <td>
                                    <a href="{{ route('ticket-types.manage', $event->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-cog"></i> Manage Ticket Types
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        @if($events->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">No events found</td>
                            </tr>
                        @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
