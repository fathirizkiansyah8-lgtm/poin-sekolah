@extends('layouts.app')
@section('title', 'Data Siswa')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title h3">Data Siswa</h1>
            <p class="page-subtitle">Daftar siswa yang terdaftar pada sistem.</p>
        </div>
        @if (auth()->user()->role === 'admin') 
            <a href="{{ route('students.create') }}" class="btn btn-primary">Tambah Siswa</a>
        @endif
    </div>
    <div class="app-card mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <input type="text" name="q" class="form-control" value="{{ request('q') }}"
                        placeholder="Cari NIS atau nama siswa">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Cari</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="app-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                                <tr>
                                    <td class="fw-semibold">{{ $student->nis }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->schoolClass->name }}</td>
                                    <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>
                                        <span class="badge {{ $student->status === 'active' ? 'text-bg-success' :
                        'text-bg-secondary' }}">
                                            {{ $student->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('students.show', $student) }}"
                                            class="btn btn-sm btn-outline-primary">Detail</a>
                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('students.edit', $student) }}"
                                                class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <form method="POST" action="{{ route('students.destroy', $student) }}" class="d-inline"
                                                onsubmit="return confirm('Hapus data siswa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">
                                Data siswa belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $students->links() }}</div>
@endsection