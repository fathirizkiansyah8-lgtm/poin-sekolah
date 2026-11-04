@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title h3">Data Kelas</h1>
            <p class="page-subtitle">Kelola kelas yang digunakan pada data siswa.</p>
        </div>
        <a href="{{ route('classes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Kelas
        </a>
    </div>

    <div class="app-card overflow-hidden">
        <div class="card-header">
            <h6 class="section-title mb-0"><i class="bi bi-building me-2 text-primary"></i>Daftar Kelas</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Tahun Ajaran</th>
                        <th>Siswa</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classes as $class)
                        <tr>
                            <td class="fw-semibold">{{ $class->name }}</td>
                            <td>
                                <span class="badge text-bg-light text-dark border">{{ $class->grade }}</span>
                            </td>
                            <td class="text-muted">{{ $class->major }}</td>
                            <td class="text-muted">{{ $class->academic_year }}</td>
                            <td>
                                <span class="badge text-bg-primary">
                                    <i class="bi bi-people me-1"></i>{{ $class->students_count }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('classes.show', $class) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="{{ route('classes.destroy', $class) }}" class="d-inline"
                                    onsubmit="return confirm('Hapus Kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                Data Kelas belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $classes->links() }}</div>
@endsection
