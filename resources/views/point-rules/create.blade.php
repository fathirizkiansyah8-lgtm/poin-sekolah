@extends('layouts.app')
@section('title', 'Tambah Aturan Poin')
@section('content')
<div class="mb-4">
    <h1 class="page-title h3">Tambah Aturan Poin</h1>
    <p class="page-subtitle">Tambahkan master pelanggaran atau reward.</p>
</div>
<div class="app-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('point-rules.store') }}">
            @csrf
            @include('point-rules._form')
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">Simpan Aturan</button>
                <a href="{{ route('point-rules.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection