@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
    <div class="mb-4">
        <h1 class="page-title h3">Ubah Password</h1>
        <p class="page-subtitle">Gunakan password saat ini untuk mengatur password baru akunmu.</p>
    </div>

    <div class="app-card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('student.password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label">Password saat ini</label>
                    <input id="current_password" type="password" name="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        autocomplete="current-password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password baru</label>
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        autocomplete="new-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Minimal 8 karakter.</div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi password baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control" autocomplete="new-password" required>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i>Simpan Password
                    </button>
                    <a href="{{ route('student.points') }}" class="btn btn-outline-secondary">Kembali ke Poin Saya</a>
                </div>
            </form>
        </div>
    </div>
@endsection