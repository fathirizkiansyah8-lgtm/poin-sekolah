@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('content')
<div class="mb-4">
    <h1 class="page-title h3">Edit Pengguna</h1>
    <p class="page-subtitle">Perbarui informasi akun pengguna.</p>
</div>

<div class="app-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('users._form')

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Perbarui Pengguna</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection