@extends('layouts.app')
@section('title', 'Detail Siswa')
@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-title h3">{{ $student->user->name }}</h1>
            <p class="page-subtitle">
                NIS {{ $student->nis }} · {{ $student->schoolClass->name }}
            </p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Kembali</a>
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
    <div class="app-card overflow-hidden mb-4">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
            <h5 class="mb-0">Riwayat Poin</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                            <td>{{ $item->occurred_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge {{ $item->type === 'reward' ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ $item->type === 'reward' ? 'Reward' : 'Pelanggaran' }}
                                </span>
                            </td>
                            <td>{{ $item->rule->name }}</td>
                            <td class="fw-semibold">{{ $item->points }}</td>
                            <td>{{ $item->recorder->name }}</td>
                            <td>
                                @if ($item->evidence_path)
                                    <a href="{{ asset('storage/' . $item->evidence_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-secondary">Lihat</a>
                                @else
                                    <span class="text-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">Belum ada riwayat
                                poin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-4">{{ $transactions->links() }}</div>
    <div class="app-card">
        <div class="card-body p-4">
            <h5 class="mb-3">Tambah Tindak Lanjut / Apresiasi</h5>
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
                        <textarea name="notes" class="form-control" rows="3" required>{{ old('notes')
        }}</textarea>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Simpan Tindak Lanjut</button>
            </form>
        </div>
    </div>
@endsection