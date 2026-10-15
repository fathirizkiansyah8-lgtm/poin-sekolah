<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-gray-900">
    <main class="mx-auto flex min-h-screen max-w-4xl items-center justify-center p-6">
        <div class="text-center">
            <h1 class="text-2xl font-semibold">Let's get started</h1>
            <p class="mt-2 text-gray-600">Laravel application is ready.</p>
            @auth
                <a class="mt-6 inline-block rounded-sm bg-gray-900 px-5 py-2 text-sm text-white" href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                @if (Route::has('login'))
                    <a class="mt-6 inline-block rounded-sm bg-gray-900 px-5 py-2 text-sm text-white" href="{{ route('login') }}">Log in</a>
                @endif
            @endauth
        </div>
    </main>
</body>
</html>
