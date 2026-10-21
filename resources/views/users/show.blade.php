@extends('layouts.app')
@section('title', 'Detail Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title h3">Detail Pengguna</h1>
        <p class="page-subtitle">Informasi lengkap akun pengguna sistem.</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">← Kembali</a>
</div>

<div class="app-card">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label text-muted small">Role / Peran</label>
                <div>
                    @if($user->role === 'admin')
                        <span class="badge bg-purple text-purple bg-opacity-10 border border-purple px-2 py-1" style="background-color: #f3e8ff; color: #6b21a8;">Admin</span>
                    @else
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1">Guru</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small">NIP</label>
                <div class="fw-semibold">{{ $user->nip ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small">Nama Lengkap</label>
                <div class="fw-semibold">{{ $user->name }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small">Username</label>
                <div class="fw-semibold">{{ $user->username }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small">Email</label>
                <div class="fw-semibold">{{ $user->email }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-muted small">Status Akun</label>
                <div>
                    @if($user->status === 'active')
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit Data</a>
            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection