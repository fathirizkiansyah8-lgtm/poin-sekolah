@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title h3">Dashboard</h1>
        <p class="page-subtitle">Ringkasan sistem poin siswa hari ini.</p>
    </div>
    <span class="badge text-bg-secondary py-2 px-3" style="font-size:0.82rem;">
        <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}
    </span>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="card-body p-4 d-flex gap-3 align-items-start">
                <div class="stat-icon icon-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Siswa Aktif</div>
                    <div class="stat-value">{{ $totalStudents }}</div>
                    <div class="mt-2 text-muted" style="font-size:0.8rem;">Siswa terdaftar &amp; aktif</div>
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
                    <div class="stat-label">Pelanggaran Hari Ini</div>
                    <div class="stat-value text-danger">{{ $todayViolations }}</div>
                    <div class="mt-2 text-muted" style="font-size:0.8rem;">Tercatat hari ini</div>
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
                    <div class="stat-label">Reward Hari Ini</div>
                    <div class="stat-value text-success">{{ $todayRewards }}</div>
                    <div class="mt-2 text-muted" style="font-size:0.8rem;">Tercatat hari ini</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="app-card">
            <div class="card-header">
                <h6 class="section-title"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Akses Cepat</h6>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-primary btn-sm px-3">
                        <i class="bi bi-people me-1"></i>Data Siswa
                    </a>
                    <a href="{{ route('point-transactions.create', ['type' => 'violation']) }}" class="btn btn-outline-danger btn-sm px-3">
                        <i class="bi bi-exclamation-circle me-1"></i>Input Pelanggaran
                    </a>
                    <a href="{{ route('point-transactions.create', ['type' => 'reward']) }}" class="btn btn-outline-success btn-sm px-3">
                        <i class="bi bi-award me-1"></i>Input Reward
                    </a>
                    <a href="{{ route('point-transactions.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="bi bi-clock-history me-1"></i>Riwayat Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
