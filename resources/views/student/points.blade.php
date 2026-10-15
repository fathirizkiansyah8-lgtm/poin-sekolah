@extends('layouts.app')
@section('title', 'Poin Saya')
@section('content')
    <div class="mb-4">
        <h1 class="page-title h3">Poin Saya</h1>
        <p class="page-subtitle">
            {{ $student->user->name }} · NIS {{ $student->nis }} · {{ $student->schoolClass->name }}
        </p>
    </div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card danger h-100">
             <div class="card-body p-4">
                <div class="stat-label">Poin Pelanggaran</div>
                <div class="stat-value text-danger">{{ $summary['violation_points'] }}</div>
                <span class="badge text-bg-danger mt-2">{{ $summary['violation_status'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card success h-100">
            <div class="card-body p-4">
                <div class="stat-label">Poin Reward</div>
                <div class="stat-value text-success">{{ $summary['reward_points'] }}</div>
                <span class="badge text-bg-success mt-2">{{ $summary['reward_level'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card h-100">
            <div class="card-body p-4">
                <div class="stat-label">Net Point</div>
                <div class="stat-value">{{ $summary['net_points'] }}</div>
               <span class="badge text-bg-secondary mt-2">Indikator informatif</span>
            </div>
        </div>
    </div>
</div>

<div class="app-card overflow-hidden">
    <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
        <h5 class="mb-0">Riwayat Poin Saya</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
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
                        <td>{{ $transaction->occurred_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge {{ $transaction->type === 'reward' ? 'text-bg-success' :'text-bg-danger' }}">
                                 {{ $transaction->type === 'reward' ? 'Reward' : 'Pelanggaran' }}
                            </span>
                        </td>
                        <td>{{ $transaction->rule->name }}</td>
                        <td class="fw-semibold">{{ $transaction->points }}</td>
                        <td>{{ $transaction->notes ?? '-' }}</td>
                    </tr>
                 @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-5">Belum ada riwayat poin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $transactions->links() }}</div>


{{-- Riwayat Follow Up --}}
<div class="app-card overflow-hidden mt-4">
    <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
        <h5 class="mb-1">Riwayat Follow Up Saya</h5>
        <p class="text-secondary small mb-0">
            Informasi tindak lanjut yang diberikan kepada kamu.
        </p>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
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
                        <td class="text-nowrap">
                            {{ $followUp->follow_up_date->format('d/m/Y') }}
                        </td>
                        <td>
                            @php
                                $types = [
                                    'coaching' => 'Coaching',
                                    'warning' => 'Peringatan',
                                    'parent_call' => 'Pemanggilan Orang Tua',
                                    'appreciation' => 'Apresiasi',
                                    'certificate' => 'Sertifikat',
                                    'other' => 'Lainnya',
                                ];
                            @endphp

                            <span class="badge text-bg-primary">
                                {{ $types[$followUp->follow_up_type] ?? 'Lainnya' }}
                            </span>
                        </td>
                        <td style="min-width: 220px; white-space: pre-line;">{{ $followUp->notes }}</td>
                        <td>
                            {{ $followUp->handler->name ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="text-center text-secondary py-5">
                            <div class="mb-2">
                                <i class="bi bi-clipboard-check fs-3"></i>
                            </div>
                            Belum ada riwayat follow up.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection