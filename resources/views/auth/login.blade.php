<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Poin Sekolah</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="auth-page">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card auth-card">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <span class="badge text-bg-primary mb-3"> Sistem Poin Sekolah</span>
                        <h2 class="fw-bold mb-2">Selamat Datang</h2>
                        <p class="text-secondary mb-0">
                            Masuk Menggunakan akun admin,Guru/BK, Siswa.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.process') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                     autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="password-field">
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    >
                                <i class="bi bi-eye-slash password-field-icon" id="togglePassword" aria-hidden="true"></i>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 py-2">Login</button>
                    </form>   
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/password.js') }}"></script>
</body>
</html>