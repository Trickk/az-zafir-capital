<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Al-Zafir Capital' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="az-bg-main min-h-screen flex flex-col">

<div class="az-skyline-glow"></div>
<div class="az-skyline"></div>

<header class="sticky top-0 z-50 bg-black/80 backdrop-blur-md az-border">
    <div class="az-container py-5 flex items-center justify-between gap-6">

        <a href="{{ route('home') }}" class="flex items-center gap-4">

            <img
                src="{{ asset('images/al-zafir-logo.png') }}"
                alt="Al-Zafir Capital"
                class="h-16 md:h-20 w-auto az-logo-glow"
            >

            <div class="hidden md:block">
                <div class="az-title text-3xl leading-none text-[var(--az-gold)]">AL-ZAFIR</div>
                <div class="text-xs tracking-[0.45em] text-[var(--az-muted)] mt-1">CAPITAL</div>
            </div>

        </a>

        <nav class="hidden md:flex items-center gap-6">
            <a href="{{ route('home') }}" class="az-nav-link">Home</a>
            <a href="{{ route('about') }}" class="az-nav-link">About</a>
            <a href="{{ route('companies') }}" class="az-nav-link">Companies</a>
            <a href="{{ route('investments') }}" class="az-nav-link">Investments</a>
            <a href="{{ route('contact') }}" class="az-nav-link">Contact</a>
        </nav>

    </div>
</header>

<main class="flex-1 relative z-10">
    @yield('content')
</main>

<footer class="mt-20 border-t border-[var(--az-line)] bg-[#080808]">
    <div class="az-container py-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="text-sm az-muted">
            © {{ date('Y') }} Al-Zafir Capital
        </div>

        <div class="text-xs md:text-sm uppercase tracking-[0.25em] text-[var(--az-gold)]">
            Private Capital · Global Vision · Strategic Growth
        </div>
    </div>
</footer>

</body>
</html>
