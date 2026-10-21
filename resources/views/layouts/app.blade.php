<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Poin Sekolah')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar no-print">
    <div class="container">
        <a class="navbar-brand fw-bold"
        href="{{ auth()->user()->role === 'student' ? route('student.points') : route('dashboard') }}">
        Poin Sekolah
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
        <div class="navbar-nav me-auto gap-lg-1 mt-3 mt-lg-0">
            @if (in_array(auth()->user()->role, ['admin', 'teacher']))
                <a class="app-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">Dashboard</a>
                <a class="app-nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}"
                    href="{{ route('students.index') }}">Siswa</a>
                <a class="app-nav-link {{ request()->routeIs('point-transactions.*') ? 'active' : ''}}"
                    href="{{ route('point-transactions.index') }}">Transaksi Poin</a>
                
            @endif

            @if (auth()->user()->role === 'admin')

                <a class="app-nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}"
                    href="{{ route('classes.index') }}">Kelas</a>
                <a class="app-nav-link {{ request()->routeIs('point-rules.*') ? 'active' : '' }}"
                    href="{{ route('point-rules.index') }}">Aturan Poin</a>
                <a class="app-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                    href="{{ route('users.index') }}">Pengguna</a> 
                
            @endif
            @if (auth()->user()->role === 'student')
                <a class="app-nav-link {{ request()->routeIs('student.points') ? 'active' : '' }}"
                    href="{{ route('student.points') }}">Poin Saya</a>
             @endif
        </div>
        <div class="d-flex align-items-center gap-3 text-white mt-3 mt-lg-0">
            <div class="text-end d-none d-md-block">
                <div class="d-inline small fw-semibold">{{ auth()->user()->name }}</div>
                <div class="d-inline small opacity-75 text-capitalize">{{ auth()->user()->role }}</div>
            </div>
 
            <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4 py-md-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data yang diisi.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>