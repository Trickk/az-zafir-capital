<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    @include('partials.head')
</head>
<body class="h-full bg-[var(--az-black)] text-[var(--az-text)] antialiased">

    <div class="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">

        <aside class="hidden lg:flex lg:flex-col border-r border-[var(--az-line)] bg-[#090909]">
            <div class="px-6 py-6 border-b border-[var(--az-line)]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4">
                    <img
                        src="{{ asset('images/al-zafir-logo.png') }}"
                        alt="Al-Zafir Capital"
                        class="h-14 w-auto az-logo-glow"
                    >

                    <div>
                        <div class="az-title text-2xl leading-none text-[var(--az-gold)]">
                            AL-ZAFIR
                        </div>
                        <div class="mt-1 text-[11px] tracking-[0.35em] text-[var(--az-muted)] uppercase">
                            Administración
                        </div>
                    </div>
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="az-admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge fa-l"></i> Panel principal
                </a>

                <a href="{{ route('admin.gangs.index') }}" class="az-admin-link {{ request()->routeIs('admin.gangs.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users fa-l"></i>Bandas
                </a>

                <a href="{{ route('admin.companies.index') }}" class="az-admin-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building fa-l"></i> Empresas
                </a>

                <a href="{{ route('admin.invoices.index') }}" class="az-admin-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar fa-l"></i> Facturas
                </a>

                <a href="{{ route('admin.cash-deliveries.index') }}" class="az-admin-link {{ request()->routeIs('admin.cash-deliveries.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-handshake fa-l"></i> Entregas de dinero
                </a>

                <a href="" class="az-admin-link">
                    <i class="fa-solid fa-piggy-bank fa-l"></i> Fondo Matrix
                </a>

                <a href="" class="az-admin-link">
                    <i class="fa-solid fa-gears fa-l"></i> Ajustes
                </a>
            </nav>

            <div class="p-4 border-t border-[var(--az-line)]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full az-btn az-btn-secondary"
                    >
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        <div class="min-h-screen flex flex-col">

            <header class="border-b border-[var(--az-line)] bg-black/40 backdrop-blur-md">
                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div>
                        <h1 class="az-title text-3xl">
                            {{ $title ?? 'Panel de administración' }}
                        </h1>
                        <p class="mt-1 text-sm az-muted">
                            Entorno privado de operaciones
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:block text-sm az-muted">
                            {{ auth()->user()?->name }}
                        </div>

                        <div class="h-10 w-10 rounded-full border border-[var(--az-line)] bg-[#111] flex items-center justify-center text-[var(--az-gold)] font-semibold">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-6 py-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @fluxScripts
</body>
</html>
