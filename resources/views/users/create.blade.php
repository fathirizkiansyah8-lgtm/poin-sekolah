@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('content')
<div class="mb-4">
    <h1 class="page-title h3">Tambah Pengguna</h1>
    <p class="page-subtitle">Buat akun login untuk Admin atau Guru.</p>
</div>

<div class="app-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form')

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection