@extends('layouts.app')
@section('title', 'Aturan Poin')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title h3">Aturan Poin</h1>
            <p class="page-subtitle">Master pelanggaran dan reward beserta bobot poinnya.</p>
        </div>
        <a href="{{ route('point-rules.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Aturan
        </a>
    </div>

    <div class="app-card overflow-hidden">
        <div class="card-header">
            <h6 class="section-title mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Daftar Aturan</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Nama Aturan</th>
                        <th>Poin</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pointRules as $pointRule)
                        <tr>
                            <td>
                                <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded" style="font-size:0.8rem;">
                                    {{ $pointRule->code }}
                                </code>
                            </td>
                            <td>
                                <span class="badge {{ $pointRule->type === 'reward' ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ $pointRule->type === 'reward' ? 'Reward' : 'Pelanggaran' }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $pointRule->category }}</td>
                            <td class="fw-semibold">{{ $pointRule->name }}</td>
                            <td>
                                <span class="fw-bold {{ $pointRule->type === 'reward' ? 'text-success' : 'text-danger' }}">
                                    {{ $pointRule->type === 'reward' ? '+' : '-' }}{{ $pointRule->points }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $pointRule->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $pointRule->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('point-rules.edit', $pointRule) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('point-rules.destroy', $pointRule) }}" class="d-inline"
                                    onsubmit="return confirm('Hapus aturan poin ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                Aturan poin belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $pointRules->links() }}</div>
@endsection
