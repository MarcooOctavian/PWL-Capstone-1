@extends('layouts.admin')

@section('title', 'Manage Waiting List')

@section('content')
    <div class="row">
        <div class="col-12">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Daftar Antrean Pembeli (Waiting List)</h3>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pembeli</th>
                            <th>Event</th>
                            <th>Jenis Tiket</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi (Ubah Status)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($waitingLists as $list)
                            <tr>
                                <td>{{ $list->id }}</td>
                                <td>{{ $list->user->name ?? 'User Tidak Diketahui' }}</td>
                                <td>{{ $list->event->title ?? 'N/A' }}</td>
                                <td>{{ $list->ticketType->name ?? 'N/A' }}</td>
                                <td>{{ $list->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($list->status == 'waiting')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($list->status == 'notified')
                                        <span class="badge badge-info">Dihubungi</span>
                                    @elseif($list->status == 'purchased')
                                        <span class="badge badge-success">Terbeli</span>
                                    @else
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('waiting-list.update', $list->id) }}" method="POST" class="form-inline">
                                        @csrf
                                        @method('PUT') <select name="status" class="form-control form-control-sm mr-2">
                                            <option value="waiting" {{ $list->status == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                            <option value="notified" {{ $list->status == 'notified' ? 'selected' : '' }}>Notified</option>
                                            <option value="purchased" {{ $list->status == 'purchased' ? 'selected' : '' }}>Purchased</option>
                                            <option value="canceled" {{ $list->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada antrean di Waiting List.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    {{ $waitingLists->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
