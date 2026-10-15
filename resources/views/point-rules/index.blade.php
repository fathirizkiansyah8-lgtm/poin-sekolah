@extends('layouts.app')
@section('title', 'Aturan Poin')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title h3">Aturan Poin</h1>
            <p class="page-subtitle">Master pelanggaran dan reward beserta bobot poinnya.</p>
        </div>
        <a href="{{ route('point-rules.create') }}" class="btn btn-primary">Tambah Aturan</a>
    </div>
    <div class="app-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                                    <td class="fw-semibold">{{ $pointRule->code }}</td>
                                    <td>
                                        <span class="badge {{ $pointRule->type === 'reward' ? 'text-bg-success' :
                        'text-bg-danger' }}">
                                            {{ $pointRule->type === 'reward' ? 'Reward' : 'Pelanggaran' }}
                                        </span>
                                    </td>
                                    <td>{{ $pointRule->category }}</td>
                                    <td>{{ $pointRule->name }}</td>
                                    <td class="fw-semibold">{{ $pointRule->points }}</td>
                                    <td>
                                        <span class="badge {{ $pointRule->is_active ? 'text-bg-primary' : 'text-bg-secondary' }}">
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
                            <td colspan="7" class="text-center text-secondary py-5">Aturan poin belum
                                tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $pointRules->links() }}</div>
@endsection