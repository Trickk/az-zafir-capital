<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin · Al-Zafir Capital' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="az-bg-main text-[var(--az-text)] min-h-screen">

<div class="min-h-screen grid lg:grid-cols-[280px_1fr]">

    <aside class="border-r border-[var(--az-line)] bg-[#090909]">
        <div class="p-6 border-b border-[var(--az-line)]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4">
                <img src="{{ asset('images/al-zafir-logo.png') }}" alt="Al-Zafir Capital" class="h-14 w-auto az-logo-glow">
                <div>
                    <div class="az-title text-2xl text-[var(--az-gold)] leading-none">AL-ZAFIR</div>
                    <div class="text-[11px] tracking-[0.35em] text-[var(--az-muted)] mt-1">ADMIN</div>
                </div>
            </a>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="az-admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.gangs') }}" class="az-admin-link {{ request()->routeIs('admin.gangs.*') ? 'active' : '' }}">Gangs</a>
            <a href="{{ route('admin.holdings') }}" class="az-admin-link {{ request()->routeIs('admin.holdings.*') ? 'active' : '' }}">Holdings</a>
            <a href="{{ route('admin.invoices') }}" class="az-admin-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">Invoices</a>
            <a href="{{ route('admin.settlements') }}" class="az-admin-link {{ request()->routeIs('admin.settlements.*') ? 'active' : '' }}">Settlements</a>
            <a href="{{ route('admin.cash-rolls') }}" class="az-admin-link {{ request()->routeIs('admin.cash-rolls.*') ? 'active' : '' }}">Cash Rolls</a>
        </nav>
    </aside>

    <div class="min-h-screen flex flex-col">
        <header class="border-b border-[var(--az-line)] bg-black/40">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="az-title text-3xl">{{ $heading ?? 'Admin Panel' }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-sm az-muted">
                        {{ auth()->user()->name ?? 'User' }}
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="az-btn az-btn-secondary">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
