@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Add New User</h3>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="3">User</option>
                                <option value="2">Organizer</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('users.index') }}" class="btn btn-default">Cancel</a>
                        <button class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
