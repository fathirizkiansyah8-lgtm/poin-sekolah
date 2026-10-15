@extends('layouts.app')
@section('title', 'Tambah Kelas')
@section('content')
<div class="mb-4">
    <h1 class="page-title h3">Tambah Kelas</h1>
    <p class="page-subtitle">Masukkan Data Kelas Baru.</p>
</div>

<di class="app-card">
    <div class="card-body py-4">
        <form method="POST" action="{{ route('classes.store') }}">
            @csrf
            @include('classes._form')

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</di>
@endsection