@extends('layouts.app')
@section('title', 'Edit Siswa')
@section('content')
 <div class="mb-4">
    <h1 class="page-title h3">Edit Siswa</h1>
    <p class="page-subtitle">Perbarui akun dan data akademik siswa.</p>
</div>

<div class="app-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf
            @method('PUT')
            @include('students._form')

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection