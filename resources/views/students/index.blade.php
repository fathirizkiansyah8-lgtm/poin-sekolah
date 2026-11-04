@extends('layouts.app')
@section('title', 'Data Siswa')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title h3">Data Siswa</h1>
            <p class="page-subtitle">Daftar siswa yang terdaftar pada sistem.</p>
        </div>
        @if (auth()->user()->role === 'admin')
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('students.export') }}" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i>Export Excel
                </a>
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i>Tambah Siswa
                </a>
            </div>
        @endif
    </div>

    @if (auth()->user()->role === 'admin')
        <div class="app-card mb-3">
            <div class="card-body p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h6 class="section-title mb-1">Import data siswa</h6>
                    <small class="text-muted">Pilih CSV yang diekspor dari Excel. Kolom wajib: nis, name, email, class_name,
                        grade, gender, status, password.</small>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('students.import') }}" enctype="multipart/form-data"
                        class="d-flex flex-wrap gap-2">
                        @csrf
                        <input type="file" name="file" accept=".csv,.txt,text/csv" class="form-control" required>
                        <button type="submit" class="btn btn-outline-primary text-nowrap">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i>Import CSV
                        </button>
                        <a href="{{ route('students.template') }}" class="btn btn-outline-success text-nowrap">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i>Download Template
                        </a>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="app-card mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0 ps-1" value="{{ request('q') }}"
                            placeholder="Cari NIS atau nama siswa…">
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary px-4">Cari</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="app-card overflow-hidden">
        <div class="card-header">
            <h6 class="section-title mb-0"><i class="bi bi-people me-2 text-primary"></i>Daftar Siswa</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
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
                            <td class="fw-semibold text-muted" style="font-size:0.85rem;">{{ $student->nis }}</td>
                            <td class="fw-semibold">{{ $student->user->name }}</td>
                            <td>
                                <span class="badge text-bg-light text-dark border" style="font-weight:500;">
                                    {{ $student->schoolClass->name }}
                                </span>
                            </td>
                            <td>
                                @if($student->gender === 'L')
                                    <i class="bi bi-gender-male text-primary me-1"></i>Laki-laki
                                @else
                                    <i class="bi bi-gender-female text-danger me-1"></i>Perempuan
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge {{ $student->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
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
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
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