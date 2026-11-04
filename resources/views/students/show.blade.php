@extends('layouts.app')
@section('title', 'Detail Siswa')
@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-title h3">{{ $student->user->name }}</h1>
            <p class="page-subtitle">
                <i class="bi bi-card-text me-1"></i>NIS {{ $student->nis }}
                &nbsp;·&nbsp;
                <i class="bi bi-building me-1"></i>{{ $student->schoolClass->name }}
            </p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
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
    <div class="app-card overflow-hidden mb-4">
        <div class="card-header">
            <h6 class="section-title mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Poin</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Aturan</th>
                        <th>Poin</th>
                        <th>Pencatat</th>
                        <th>Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $item)
                        <tr>
                            <td class="text-nowrap text-muted" style="font-size:0.85rem;">
                                {{ $item->occurred_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <span class="badge {{ $item->type === 'reward' ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ $item->type === 'reward' ? 'Reward' : 'Pelanggaran' }}
                                </span>
                            </td>
                            <td>{{ $item->rule->name }}</td>
                            <td>
                                <span class="fw-bold {{ $item->type === 'reward' ? 'text-success' : 'text-danger' }}">
                                    {{ $item->type === 'reward' ? '+' : '-' }}{{ $item->points }}
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:0.88rem;">{{ $item->recorder->name }}</td>
                            <td>
                                @if ($item->evidence_path)
                                    <a href="{{ asset('storage/' . $item->evidence_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-paperclip me-1"></i>Lihat
                                    </a>
                                @else
                                    <span class="text-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                Belum ada riwayat poin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-4">{{ $transactions->links() }}</div>

    @if (auth()->user()->role === 'admin')
        <div class="app-card overflow-hidden mb-4">
            <div class="card-header">
                <h6 class="section-title mb-0">
                    <i class="bi bi-journal-text me-2 text-primary"></i>Riwayat Perubahan Data
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Diubah oleh</th>
                            <th>Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($studentChanges as $change)
                            <tr>
                                <td class="text-nowrap text-muted" style="font-size:0.85rem;">
                                    {{ $change->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $change->actor_name }}</div>
                                    <small class="text-muted">
                                        {{ ['admin' => 'Admin', 'teacher' => 'Guru', 'student' => 'Siswa'][$change->actor_role] ?? $change->actor_role }}
                                    </small>
                                </td>
                                <td>
                                    @foreach ($change->changes as $field => $values)
                                        @php
                                            $labels = [
                                                'name' => 'Nama',
                                                'email' => 'Email',
                                                'nis' => 'NIS',
                                                'school_class' => 'Kelas',
                                                'gender' => 'Jenis kelamin',
                                                'status' => 'Status',
                                                'password' => 'Password',
                                            ];
                                            $displayValues = [
                                                'L' => 'Laki-laki',
                                                'P' => 'Perempuan',
                                                'active' => 'Aktif',
                                                'inactive' => 'Tidak aktif',
                                            ];
                                        @endphp
                                        <div>
                                            <span class="fw-semibold">{{ $labels[$field] ?? $field }}:</span>
                                            @if ($field === 'password')
                                                Password diubah (nilai dirahasiakan)
                                            @else
                                                {{ $displayValues[$values['old']] ?? $values['old'] }}
                                                &rarr;
                                                {{ $displayValues[$values['new']] ?? $values['new'] }}
                                            @endif
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-4">
                                    Belum ada perubahan data yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Form Follow Up --}}
    <div class="app-card">
        <div class="card-header">
            <h6 class="section-title mb-0">
                <i class="bi bi-chat-dots me-2 text-primary"></i>Tambah Tindak Lanjut / Apresiasi
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('students.follow-ups.store', $student) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Tindak Lanjut</label>
                        <select name="follow_up_type" class="form-select" required>
                            <option value="coaching">Pembinaan</option>
                            <option value="warning">Peringatan</option>
                            <option value="parent_call">Panggilan Orang Tua</option>
                            <option value="appreciation">Apresiasi</option>
                            <option value="certificate">Sertifikat</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="follow_up_date" class="form-control"
                            value="{{ old('follow_up_date', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3" required>{{ old('notes') }}</textarea>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">
                    <i class="bi bi-check2 me-1"></i>Simpan Tindak Lanjut
                </button>
            </form>
        </div>
    </div>
@endsection
