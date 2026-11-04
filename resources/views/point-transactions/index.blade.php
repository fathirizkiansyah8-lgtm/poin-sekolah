@extends('layouts.app')
@section('title', 'Transaksi Poin')
@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="page-title h3">Transaksi Poin</h1>
            <p class="page-subtitle">Riwayat poin pelanggaran dan reward siswa.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('point-transactions.create', ['type' => 'violation']) }}" class="btn btn-outline-danger">
                <i class="bi bi-exclamation-circle me-1"></i>Input Pelanggaran
            </a>
            <a href="{{ route('point-transactions.create', ['type' => 'reward']) }}" class="btn btn-success">
                <i class="bi bi-award me-1"></i>Input Reward
            </a>
        </div>
    </div>

    <div class="app-card mb-3">
        <div class="card-header">
            <h6 class="section-title mb-0"><i class="bi bi-funnel me-2 text-primary"></i>Filter</h6>
        </div>
        <div class="card-body p-3">
            <form method="GET" action="{{ route('point-transactions.index') }}" class="row g-2">
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="violation" @selected(request('type') === 'violation')>Pelanggaran</option>
                        <option value="reward" @selected(request('type') === 'reward')>Reward</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="student_id" class="form-select">
                        <option value="">Semua Siswa</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                {{ $student->nis }} - {{ $student->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="class_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"
                        title="Dari tanggal">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"
                        title="Sampai tanggal">
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-primary">Filter</button>
                </div>
                <div class="col-12">
                    <a href="{{ route('point-transactions.index') }}" class="small text-decoration-none text-muted">
                        <i class="bi bi-x-circle me-1"></i>Reset filter
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="app-card overflow-hidden">
        <div class="card-header">
            <h6 class="section-title mb-0"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Riwayat Transaksi</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jenis</th>
                        <th>Aturan</th>
                        <th>Poin</th>
                        <th>Pencatat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="text-nowrap text-muted" style="font-size:0.85rem;">
                                {{ $transaction->occurred_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="fw-semibold">{{ $transaction->student->user->name }}</td>
                            <td>
                                <span class="badge text-bg-light text-dark border" style="font-weight:500;">
                                    {{ $transaction->student->schoolClass->name }}
                                </span>
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
                            <td class="text-muted" style="font-size:0.88rem;">{{ $transaction->recorder->name }}</td>
                            <td class="text-end">
                                <a href="{{ route('point-transactions.show', $transaction) }}"
                                    class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-secondary py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                Transaksi belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $transactions->links() }}</div>
@endsection
