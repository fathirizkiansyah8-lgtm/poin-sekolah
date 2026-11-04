@extends('layouts.app')
@section('title', 'Data Pengguna')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title h3">Data Pengguna</h1>
            <p class="page-subtitle">Daftar pengguna (Admin &amp; Guru) yang terdaftar pada sistem.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>Tambah Pengguna
        </a>
    </div>

    <div class="app-card mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('users.index') }}" class="d-flex gap-2">
                <div class="input-group" style="max-width: 460px;">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari NIP, Nama, Email, atau Username…"
                        class="form-control border-start-0 ps-1">
                </div>
                <button type="submit" class="btn btn-primary px-4">Cari</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="app-card overflow-hidden">
        <div class="card-header">
            <h6 class="section-title mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>Daftar Pengguna</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Username / Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="fw-semibold text-muted" style="font-size:0.85rem;">{{ $user->nip ?? '-' }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>
                                <div>{{ $user->username }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge text-bg-purple">Admin</span>
                                @else
                                    <span class="badge text-bg-primary">Guru</span>
                                @endif
                            </td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                Tidak ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
