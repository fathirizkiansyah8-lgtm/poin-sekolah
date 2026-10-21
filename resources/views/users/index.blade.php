@extends('layouts.app')
@section('title', 'Data Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Data Pengguna</h1>
        <p class="text-muted small mb-0">Daftar pengguna (Admin & Guru) yang terdaftar pada sistem.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary fw-semibold px-3">
        Tambah Pengguna
    </a>
</div>

<!-- Bungkus Search & Tabel dalam satu Card besar yang melengkung -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden p-3">
    <!-- Form Search -->
    <div class="card-body pb-3">
        <form method="GET" action="{{ route('users.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP, Nama, Email, atau Username" class="form-control rounded-3" style="max-width: 450px;">
            <button type="submit" class="btn btn-primary px-4 rounded-3">Cari</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-3 rounded-3">Reset</a>
        </form>
    </div>

    <!-- Tabel Data -->
    <div class="table-responsive px-2">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase small text-muted border-top">
                <tr>
                    <th class="py-3 ps-3">NIP</th>
                    <th class="py-3">NAMA</th>
                    <th class="py-3">USERNAME / EMAIL</th>
                    <th class="py-3">ROLE</th>
                    <th class="py-3">STATUS</th>
                    <th class="py-3 text-end pe-3">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-3 fw-bold">{{ $user->nip ?? '-' }}</td>
                        <td class="fw-semibold text-dark">{{ $user->name }}</td>
                        <td>
                            <div>{{ $user->username }}</div>
                            <small class="text-muted">{{ $user->email }}</small>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-purple px-2 py-1" style="background-color: #e9d5ff; color: #6b21a8;">Admin</span>
                            @else
                                <span class="badge bg-primary px-2 py-1">Guru</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success px-2 py-1">Aktif</span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary rounded-2 px-2 py-1">Detail</a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary rounded-2 px-2 py-1">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 px-2 py-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection