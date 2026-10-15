@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-tittle" h3">Dashboard</h1>
        <p class="page-subtitle">Ringkasan sistem poin siswa hari ini.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="card-body p-4">
                <div class="stat-label">Total Siswa Aktif</div>
                <div class="stat-value">{{ $totalStudents }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card danger h-100">
            <div class="card-body p-4">
                <div class="stat-label">Pelanggaran Hari Ini</div>
                <div class="stat-value text-danger">{{ $todayViolations }}</div>
            </div>
        </div>   
    </div>

    <div class="col-md-4">
        <div class="stat-card success h-100">
            <div class="card-body p-4">
                <div class="stat-label">Reward Hari Ini</div>
                <div class="stat-value text-success">{{ $todayRewards }}</div>
            </div>
        </div>
    </div>
</div>
@endsection