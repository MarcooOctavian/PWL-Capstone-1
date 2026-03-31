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
                    <div class="card-tools">
                        <a href="{{ route('ticket-types.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add Ticket Type
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Max Purchase</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($ticketTypes as $tt)
                            <tr>
                                <td>{{ $tt->id }}</td>
                                <td>{{ $tt->event->title ?? 'N/A' }}</td>
                                <td>{{ $tt->name }}</td>
                                <td>Rp {{ number_format($tt->price, 0, ',', '.') }}</td>
                                <td>{{ $tt->stock }}</td>
                                <td>{{ $tt->max_purchase }}</td>
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
                            </tr>
                        @endforeach

                        @if($ticketTypes->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">No ticket types found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
