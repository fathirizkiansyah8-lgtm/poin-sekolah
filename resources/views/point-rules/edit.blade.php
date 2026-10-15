@extends('layouts.app')
@section('title', 'Edit Aturan Poin')
@section('content')
    <div class="mb-4">
        <h1 class="page-title h3">Edit Aturan Poin</h1>
        <p class="page-subtitle">Perbarui aturan {{ $pointRule->code }}.</p>
    </div>
    <div class="app-card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('point-rules.update', $pointRule) }}">
                @csrf
                @method('PUT')
                @include('point-rules._form')
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('point-rules.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection