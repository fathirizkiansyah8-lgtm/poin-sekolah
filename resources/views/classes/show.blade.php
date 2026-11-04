@extends('layouts.app')
@section('title', 'Kelas ' . $class->name)
@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title h3">Kelas {{ $class->name }}</h1>
        <p class="page-subtitle">
            <i class="bi bi-layers me-1"></i>{{ $class->grade }}
            &nbsp;·&nbsp;
            <i class="bi bi-briefcase me-1"></i>{{ $class->major }}
            &nbsp;·&nbsp;
            <i class="bi bi-calendar3 me-1"></i>{{ $class->academic_year }}
        </p>
    </div>
    <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

{{-- Ringkasan kelas --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="card-body p-4 d-flex gap-3 align-items-start">
                <div class="stat-icon icon-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Siswa Aktif</div>
                    <div class="stat-value">{{ $students->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card danger h-100">
            <div class="card-body p-4 d-flex gap-3 align-items-start">
                <div class="stat-icon icon-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Poin Pelanggaran</div>
                    <div class="stat-value text-danger">{{ $students->sum('violation_points') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card success h-100">
            <div class="card-body p-4 d-flex gap-3 align-items-start">
                <div class="stat-icon icon-success">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Poin Reward</div>
                    <div class="stat-value text-success">{{ $students->sum('reward_points') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel siswa --}}
<div class="app-card overflow-hidden">
    <div class="card-header">
        <h6 class="section-title mb-0">
            <i class="bi bi-people me-2 text-primary"></i>Daftar Siswa — {{ $class->name }}
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Gender</th>
                    <th class="text-danger">Poin Pelanggaran</th>
                    <th class="text-success">Poin Reward</th>
                    <th>Net Point</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td class="fw-semibold text-muted" style="font-size:0.85rem;">{{ $student->nis }}</td>
                        <td class="fw-semibold">{{ $student->user->name }}</td>
                        <td>
                            @if($student->gender === 'L')
                                <i class="bi bi-gender-male text-primary me-1"></i>Laki-laki
                            @else
                                <i class="bi bi-gender-female text-danger me-1"></i>Perempuan
                            @endif
                        </td>
                        <td>
                            @if($student->violation_points > 0)
                                <span class="fw-bold text-danger">{{ $student->violation_points }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td>
                            @if($student->reward_points > 0)
                                <span class="fw-bold text-success">{{ $student->reward_points }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td>
                            @php $net = $student->reward_points - $student->violation_points; @endphp
                            <span class="fw-semibold {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $net >= 0 ? '+' : '' }}{{ $net }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-5">
                            <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                            Tidak ada siswa aktif di kelas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
