@extends('layouts.app')
@section('title', 'Tambah Siswa')
@section('content')
<div class="mb-4">
    <h1 class="page-title h3">Tambah Siswa</h1>
    <p class="page-subtitle">Buat akun login dan data akademik siswa.</p>
</div>

<div class="app-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('students.store') }}"
            @csrf
            @include('students._form')

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">Simpan Siswa</button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection