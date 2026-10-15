@extends('layouts.app')
@section('title', 'Edit Kelas')
@section('content')
<div class="mb-4">
    <h1 class="page-title h3">Edit Kelas</h1>
    <p class="page-subtitle">Perbarui Data Kelas {{ $class->name }}</p>
</div>

<div class="app-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('classes.update', $class) }}">
            @csrf
            @method('PUT')
            @include('classes._form')

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection