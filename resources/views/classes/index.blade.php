@extends('layouts.app')

@section('tittle', 'Data Kelas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title h3">Data Kelas</h1>
            <p class="page-subtitle">Kelola kelas yang digunakan pada data siswa.</p>
        </div>
        <a href="{{ route('classes.create') }}" class="btn btn-primary">Tambah Kelas</a>
    </div>

    <div class="app-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                            <td>{{ $class->grade }}</td>
                            <td>{{ $class->major }}</td>
                            <td>{{ $class->academic_year }}</td>
                            <td>{{ $class->students_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                                <form method="POST" action="{{ route('classes.destroy', $class) }}" class="d-inline"
                                    onsubmit="return confirm('Hapus Kelas ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">
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