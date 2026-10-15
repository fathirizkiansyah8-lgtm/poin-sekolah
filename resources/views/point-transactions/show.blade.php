@extends('layouts.app')
@section('title', 'Detail Transaksi Poin')
@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-title h3">Detail Transaksi Poin</h1>
            <p class="page-subtitle">Informasi transaksi yang sudah tercatat.</p>
        </div>
        <a href="{{ route('point-transactions.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="app-card">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="small text-secondary">Siswa</div>
                    <div class="fw-semibold">{{ $pointTransaction->student->user->name }}</div>
                    <div class="small text-secondary">
                        {{ $pointTransaction->student->nis }} · {{ $pointTransaction->student->schoolClass->name }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-secondary">Jenis</div>
                    <span
                        class="badge mt-1 {{ $pointTransaction->type === 'reward' ? 'text-bg-success' : 'text-bg-danger' }}">
                        {{ $pointTransaction->type === 'reward' ? 'Reward' : 'Pelanggaran' }}
                    </span>
                </div>

                <div class="col-md-3">
                    <div class="small text-secondary">Poin</div>
                    <div class="h4 mb-0">{{ $pointTransaction->points }}</div>
                </div>

                <div class="col-md-6">
                    <div class="small text-secondary">Aturan</div>
                    <div class="fw-semibold">{{ $pointTransaction->rule->code }} - {{ $pointTransaction->rule->name }}</div>
                </div>

                <div class="col-md-3">
                    <div class="small text-secondary">Tanggal</div>
                    <div>{{ $pointTransaction->occurred_at->format('d/m/Y H:i') }}</div>
                </div>

                <div class="col-md-3">
                    
                    <div class="small text-secondary">Pencatat</div>
                    <div>{{ $pointTransaction->recorder->name }}</div>
                </div>

                <div class="col-md-12">
                    <div class="small text-secondary">Catatan</div>
                    <div>{{ $pointTransaction->notes ?: '-' }}</div>
                </div>
                <div class="col-md-12">
                    <div class="small text-secondary mb-2">Bukti</div>
                    @if ($pointTransaction->evidence_path)
                        <a href="{{ asset('storage/' . $pointTransaction->evidence_path) }}" target="_blank"
                            class="btn btn-sm btn-outline-primary">Buka Bukti</a>
                    @else
                        <span class="text-secondary">Tidak ada bukti.</span>
                    @endif
                </div>
            </div>

        </div>

        <hr class="my-2">

        <form method="POST" action="{{ route('point-transactions.destroy', $pointTransaction) }}"
            onsubmit="return confirm('Hapus transaksi poin ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger m-2">Hapus Transaksi</button>
        </form>
    </div>
</div>
@endsection