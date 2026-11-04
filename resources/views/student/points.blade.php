@extends('layouts.app')
@section('title', 'Poin Saya')
@section('content')
    <div class="mb-4">
        <h1 class="page-title h3">Poin Saya</h1>
        <p class="page-subtitle">
            <i class="bi bi-person me-1"></i>{{ $student->user->name }}
            &nbsp;·&nbsp;
            <i class="bi bi-card-text me-1"></i>NIS {{ $student->nis }}
            &nbsp;·&nbsp;
            <i class="bi bi-building me-1"></i>{{ $student->schoolClass->name }}
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card danger h-100">
                <div class="card-body p-4 d-flex gap-3 align-items-start">
                    <div class="stat-icon icon-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-label">Poin Pelanggaran</div>
                        <div class="stat-value text-danger">{{ $summary['violation_points'] }}</div>
                        <span class="badge text-bg-danger mt-2">{{ $summary['violation_status'] }}</span>
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
                        <div class="stat-label">Poin Reward</div>
                        <div class="stat-value text-success">{{ $summary['reward_points'] }}</div>
                        <span class="badge text-bg-success mt-2">{{ $summary['reward_level'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card h-100">
                <div class="card-body p-4 d-flex gap-3 align-items-start">
                    <div class="stat-icon icon-primary">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <div>
                        <div class="stat-label">Net Point</div>
                        <div class="stat-value">{{ $summary['net_points'] }}</div>
                        <span class="badge text-bg-secondary mt-2">Indikator informatif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Poin --}}
    <div class="app-card overflow-hidden">
        <div class="card-header">
            <h6 class="section-title mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Poin Saya</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Aturan</th>
                        <th>Poin</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="text-nowrap text-muted" style="font-size:0.85rem;">
                                {{ $transaction->occurred_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <span class="badge {{ $transaction->type === 'reward' ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ $transaction->type === 'reward' ? 'Reward' : 'Pelanggaran' }}
                                </span>
                            </td>
                            <td>{{ $transaction->rule->name }}</td>
                            <td>
                                <span class="fw-bold {{ $transaction->type === 'reward' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'reward' ? '+' : '-' }}{{ $transaction->points }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $transaction->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                Belum ada riwayat poin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $transactions->links() }}</div>

    {{-- Riwayat Follow Up --}}
    <div class="app-card overflow-hidden mt-4">
        <div class="card-header">
            <h6 class="section-title mb-0">
                <i class="bi bi-clipboard-check me-2 text-primary"></i>Riwayat Follow Up Saya
            </h6>
            <small class="text-muted">Informasi tindak lanjut yang diberikan kepada kamu.</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Follow Up</th>
                        <th>Catatan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($followUps as $followUp)
                        <tr>
                            <td class="text-nowrap text-muted" style="font-size:0.85rem;">
                                {{ $followUp->follow_up_date->format('d/m/Y') }}
                            </td>
                            <td>
                                @php
                                    $types = [
                                        'coaching'     => ['label' => 'Pembinaan',          'class' => 'text-bg-primary'],
                                        'warning'      => ['label' => 'Peringatan',          'class' => 'text-bg-warning'],
                                        'parent_call'  => ['label' => 'Panggilan Ortu',      'class' => 'text-bg-danger'],
                                        'appreciation' => ['label' => 'Apresiasi',           'class' => 'text-bg-success'],
                                        'certificate'  => ['label' => 'Sertifikat',          'class' => 'text-bg-info'],
                                        'other'        => ['label' => 'Lainnya',             'class' => 'text-bg-secondary'],
                                    ];
                                    $type = $types[$followUp->follow_up_type] ?? $types['other'];
                                @endphp
                                <span class="badge {{ $type['class'] }}">{{ $type['label'] }}</span>
                            </td>
                            <td style="min-width: 220px; white-space: pre-line;">{{ $followUp->notes }}</td>
                            <td class="text-muted" style="font-size:0.88rem;">{{ $followUp->handler->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-5">
                                <i class="bi bi-clipboard-check fs-3 d-block mb-2 opacity-50"></i>
                                Belum ada riwayat follow up.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
