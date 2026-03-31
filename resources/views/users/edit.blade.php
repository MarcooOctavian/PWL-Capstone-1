@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit User</h3>
                </div>

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                        </div>

                        <div class="form-group">
                            <label>Password (optional)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>User</option>
                                <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Organizer</option>
                                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('users.index') }}" class="btn btn-default">Cancel</a>
                        <button class="btn btn-primary">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
