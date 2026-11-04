<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Poin Sekolah')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html.single-tab-blocked body > * {
            visibility: hidden !important;
        }

        html.single-tab-blocked::after {
            content: "Akun ini sedang dibuka di tab lain. Tutup tab tersebut untuk melanjutkan.";
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: grid;
            place-items: center;
            padding: 2rem;
            background: #f4f6fa;
            color: #233047;
            font: 600 1rem/1.5 system-ui, sans-serif;
            text-align: center;
        }
    </style>
    <script>
        (() => {
            const lockName = 'point-sekolah-account-{{ auth()->id() }}';
            const blockedClass = 'single-tab-blocked';
            const blockTab = () => document.documentElement.classList.add(blockedClass);

            if (navigator.locks?.request) {
                let releaseLock;

                window.addEventListener('pagehide', () => releaseLock?.(), { once: true });

                navigator.locks.request(lockName, { mode: 'exclusive', ifAvailable: true }, async (lock) => {
                    if (!lock) {
                        blockTab();
                        return;
                    }

                    await new Promise((resolve) => {
                        releaseLock = resolve;
                    });
                }).catch(blockTab);

                return;
            }

            try {
                const storageKey = `${lockName}-fallback`;
                const token = crypto.randomUUID?.() ?? `${Date.now()}-${Math.random()}`;
                const current = JSON.parse(localStorage.getItem(storageKey) ?? 'null');

                if (current && current.expiresAt > Date.now()) {
                    blockTab();
                    return;
                }

                const writeLock = () => localStorage.setItem(storageKey, JSON.stringify({
                    token,
                    expiresAt: Date.now() + 15000,
                }));

                writeLock();
                const heartbeat = window.setInterval(writeLock, 5000);

                window.addEventListener('pagehide', () => {
                    window.clearInterval(heartbeat);

                    if (JSON.parse(localStorage.getItem(storageKey) ?? 'null')?.token === token) {
                        localStorage.removeItem(storageKey);
                    }
                }, { once: true });
            } catch {
                blockTab();
            }
        })();
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar no-print">
    <div class="container">
        <span class="navbar-brand fw-bold" style="cursor:default;">
            <span class="brand-icon"><i class="bi bi-star-fill"></i></span>
            Poin Sekolah
        </span>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <div class="navbar-nav me-auto gap-lg-1 mt-3 mt-lg-0">
                @if (in_array(auth()->user()->role, ['admin', 'teacher']))
                    <a class="app-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                    <a class="app-nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}"
                        href="{{ route('students.index') }}">
                        <i class="bi bi-people"></i> Siswa
                    </a>
                    <a class="app-nav-link {{ request()->routeIs('point-transactions.*') ? 'active' : ''}}"
                        href="{{ route('point-transactions.index') }}">
                        <i class="bi bi-arrow-left-right"></i> Transaksi Poin
                    </a>
                @endif

                @if (auth()->user()->role === 'admin')
                    <a class="app-nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}"
                        href="{{ route('classes.index') }}">
                        <i class="bi bi-building"></i> Kelas
                    </a>
                    <a class="app-nav-link {{ request()->routeIs('point-rules.*') ? 'active' : '' }}"
                        href="{{ route('point-rules.index') }}">
                        <i class="bi bi-list-check"></i> Aturan Poin
                    </a>
                    <a class="app-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                        href="{{ route('users.index') }}">
                        <i class="bi bi-person-badge"></i> Pengguna
                    </a>
                @endif

                @if (auth()->user()->role === 'student')
                    <a class="app-nav-link {{ request()->routeIs('student.points') ? 'active' : '' }}"
                        href="{{ route('student.points') }}">
                        <i class="bi bi-award"></i> Poin Saya
                    </a>
                    <a class="app-nav-link {{ request()->routeIs('student.password.*') ? 'active' : '' }}"
                        href="{{ route('student.password.edit') }}">
                        <i class="bi bi-shield-lock"></i> Ubah Password
                    </a>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <div class="navbar-user-info d-none d-md-block">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role }}</div>
                </div>
                <div class="navbar-divider d-none d-md-block"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light px-3">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4 py-md-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
            <div>
                <strong>Periksa kembali data yang diisi.</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
