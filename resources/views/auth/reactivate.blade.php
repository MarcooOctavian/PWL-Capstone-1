@extends('layouts.admin')
@section('title', 'Reactivate Account')
@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <!-- Message -->
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Account Inactive</h5>
                <p class="mb-1">
                    Akun Anda saat ini <strong>dinonaktifkan</strong> karena tidak ada aktivitas dalam jangka waktu yang lama.
                </p>
                <p class="mb-1">
                    Demi menjaga keamanan dan integritas sistem, akun yang tidak aktif selama lebih dari <strong>1 tahun</strong> akan dinonaktifkan secara otomatis.
                </p>
                <p class="mb-0">
                    Untuk kembali mengakses sistem, silakan lakukan verifikasi dengan memasukkan email dan password Anda di bawah ini.
                </p>
            </div>
            <!-- Form -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Reactivate Your Account</h3>
                </div>
                <form method="POST" action="{{ route('reactivate.process') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="Masukkan email Anda"
                                   required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Masukkan password Anda"
                                   required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-warning">
                            <i class="fas fa-user-check"></i> Reactivate Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
