<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reactivate Account</title>
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/dist/css/adminlte.min.css') }}">
    <style>
        body {
            background-color: #f4f6f9;
        }
    </style>
</head>
<body>
<div class="container mt-5">
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
            <!-- Global Error -->
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
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
</div>
<script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
