<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Poin Sekolah</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html, body { height: 100%; margin: 0; }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background-color: #eef1f8;
            background-image:
                radial-gradient(ellipse at 20% 10%, rgba(49,87,200,0.18) 0%, transparent 45%),
                radial-gradient(ellipse at 80% 90%, rgba(79,120,240,0.13) 0%, transparent 45%);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 1.4rem;
            box-shadow: 0 8px 40px rgba(30,50,120,0.13);
            overflow: hidden;
        }

        /* strip warna di bagian atas card */
        .login-card-header {
            background: linear-gradient(135deg, #2443a1 0%, #3e6ae1 100%);
            padding: 1.8rem 2rem 1.6rem;
        }
        .login-card-header .brand {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 1.2rem;
        }
        .login-card-header .brand-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: #fff;
        }
        .login-card-header .brand-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            letter-spacing: 0.2px;
        }
        .login-card-header h2 {
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .login-card-header p {
            color: rgba(255,255,255,0.65);
            font-size: 0.85rem;
            margin: 0;
        }

        /* body form */
        .login-card-body {
            padding: 1.8rem 2rem 2rem;
        }
        .login-card-body .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.35rem;
        }
        .login-card-body .form-control {
            border: 1.5px solid #dbe3f0;
            border-radius: 0.65rem;
            min-height: 44px;
            font-size: 0.92rem;
            background: #f9fafc;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .login-card-body .form-control:focus {
            background: #fff;
            border-color: #3157c8;
            box-shadow: 0 0 0 3px rgba(49,87,200,0.1);
        }
        .password-field { position: relative; }
        .password-field .form-control { padding-right: 2.8rem; }
        .password-field-icon {
            position: absolute;
            top: 50%;
            right: 0.9rem;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn-masuk {
            width: 100%;
            padding: 0.7rem;
            border: none;
            border-radius: 0.65rem;
            background: #3157c8;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: background 0.15s, box-shadow 0.15s;
        }
        .btn-masuk:hover {
            background: #2443a1;
            box-shadow: 0 4px 14px rgba(49,87,200,0.3);
        }
        .login-footer {
            text-align: center;
            margin-top: 1.4rem;
            color: #94a3b8;
            font-size: 0.78rem;
        }
    </style>
</head>
<body>
<div class="login-page">
    <div class="login-card">

        {{-- Header biru --}}
        <div class="login-card-header">
            <div class="brand">
                <span class="brand-icon"><i class="bi bi-star-fill"></i></span>
                <span class="brand-name">Poin Sekolah</span>
            </div>
            <h2>Selamat Datang</h2>
            <p>Masuk menggunakan akun Admin, Guru/BK, atau Siswa.</p>
        </div>

        {{-- Form --}}
        <div class="login-card-body">

            @if ($errors->any())
                <div class="d-flex align-items-center gap-2 mb-3 px-3 py-2"
                    style="background:#fee2e2; border-radius:0.65rem; color:#991b1b; font-size:0.87rem;">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="email@sekolah.sch.id"
                        autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="password-field">
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••">
                        <i class="bi bi-eye-slash password-field-icon" id="togglePassword"></i>
                    </div>
                </div>

                <button type="submit" class="btn-masuk">Masuk</button>
            </form>

            <div class="login-footer">Sistem Informasi Poin Siswa &copy; {{ date('Y') }}</div>
        </div>

    </div>
</div>
<script src="{{ asset('js/password.js') }}"></script>
</body>
</html>
