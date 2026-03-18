@extends('layouts.public', ['title' => 'Al-Zafir Capital'])

@section('content')

<section class="az-hero az-hero-premium min-h-[calc(100vh-96px)] flex items-center relative overflow-hidden">

    <div class="az-hero-pattern"></div>
    <div class="az-hero-glow"></div>
    <div class="az-hero-particles">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="az-container w-full relative z-10">
        <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-12 items-center">

            <div class="max-w-5xl">
                <p class="az-eyebrow mb-4">Private Investment Holding</p>

                <h1 class="az-title text-5xl md:text-7xl font-bold leading-tight">
                    Power, discretion and strategic capital.
                </h1>

                <p class="mt-8 max-w-3xl text-lg leading-8 az-muted">
                    Al-Zafir Capital is a private investment holding headquartered in Abu Dhabi,
                    focused on hospitality, technology, logistics, entertainment and cultural ventures.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('about') }}" class="az-btn az-btn-primary">
                        Discover the Group
                    </a>

                    <a href="{{ route('companies') }}" class="az-btn az-btn-secondary">
                        Explore Companies
                    </a>
                </div>

                <div class="mt-12 grid sm:grid-cols-3 gap-4 max-w-3xl">
                    <div class="az-stat-card">
                        <div class="az-stat-number">8</div>
                        <div class="az-stat-label">Core Subsidiaries</div>
                    </div>

                    <div class="az-stat-card">
                        <div class="az-stat-number">Global</div>
                        <div class="az-stat-label">Strategic Reach</div>
                    </div>

                    <div class="az-stat-card">
                        <div class="az-stat-number">Private</div>
                        <div class="az-stat-label">Capital Structure</div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:flex justify-center">
                <div class="az-hero-emblem-wrap">
                    <div class="az-hero-emblem-ring"></div>
                    <div class="az-hero-emblem-ring az-hero-emblem-ring-2"></div>

                    <img
                        src="{{ asset('images/al-zafir-logo.png') }}"
                        alt="Al-Zafir Capital"
                        class="az-hero-emblem"
                    >
                </div>
            </div>

        </div>
    </div>
</section>

<section class="az-section pt-8">
    <div class="az-container">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <p class="az-eyebrow mb-2">Global Presence</p>
                <h2 class="az-title text-4xl font-bold">Strategic international footprint</h2>
            </div>

            <div class="az-muted max-w-2xl">
                Al-Zafir Capital operates through a selective international structure,
                connecting private investment, logistics, hospitality and cultural assets
                across key global markets.
            </div>
        </div>

        <div class="az-map-card p-6 md:p-10">
            <div class="az-world-map">
               <svg class="az-map-lines" viewBox="0 0 100 100" preserveAspectRatio="none">

    <!-- Abu Dhabi → London -->
    <line class="az-main-route" x1="61.7" y1="38.5" x2="47.7" y2="18.5" />

    <!-- Abu Dhabi → Monaco -->
    <line class="az-main-route" x1="61.7" y1="38.5" x2="49.2" y2="25.5" />

    <!-- Abu Dhabi → Singapore -->
    <line class="az-main-route" x1="61.7" y1="38.5" x2="75.2" y2="55.7" />

    <!-- Abu Dhabi → Los Santos -->
    <line class="az-main-route" x1="61.7" y1="38.5" x2="17.5" y2="31.5" />

</svg>
                <div class="az-map-point is-abu-dhabi">
                    <span></span>
                    <small>Abu Dhabi</small>
                </div>

                <div class="az-map-point is-london">
                    <span></span>
                    <small>London</small>
                </div>

                <div class="az-map-point is-monaco">
                    <span></span>
                    <small>Monaco</small>
                </div>

                <div class="az-map-point is-singapore">
                    <span></span>
                    <small>Singapore</small>
                </div>

                <div class="az-map-point is-los-santos">
                    <span></span>
                    <small>Los Santos</small>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="az-section pt-6">
    <div class="az-container">
        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="az-card p-6">
                <div class="text-3xl font-bold az-gold">Global</div>
                <p class="mt-3 az-muted leading-7">
                    International capital structure with selective expansion and long-term positioning.
                </p>
            </div>

            <div class="az-card p-6">
                <div class="text-3xl font-bold az-gold">Luxury</div>
                <p class="mt-3 az-muted leading-7">
                    Premium hospitality, cultural assets and exclusive ventures aligned with prestige markets.
                </p>
            </div>

            <div class="az-card p-6">
                <div class="text-3xl font-bold az-gold">Control</div>
                <p class="mt-3 az-muted leading-7">
                    Structured operations managed with discipline, confidentiality and strategic oversight.
                </p>
            </div>

            <div class="az-card p-6">
                <div class="text-3xl font-bold az-gold">Growth</div>
                <p class="mt-3 az-muted leading-7">
                    Focused expansion into high-value urban, tourism and culturally relevant markets.
                </p>
            </div>
        </div>
    </div>
</section>



<section class="az-section pt-8">
    <div class="az-container">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <div>
                <p class="az-eyebrow mb-2">Portfolio Structure</p>
                <h2 class="az-title text-4xl font-bold">Core subsidiaries</h2>
            </div>

            <a href="{{ route('companies') }}" class="az-btn az-btn-secondary">
                View all companies
            </a>
        </div>

        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="az-card p-6">
                <p class="az-eyebrow mb-3">Cultural</p>
                <h3 class="az-title text-3xl font-semibold">Zafir Cultural Holdings</h3>
                <p class="mt-4 az-muted leading-7">
                    Cultural division focused on galleries, exhibitions and prestige-driven artistic ventures.
                </p>
            </div>

            <div class="az-card p-6">
                <p class="az-eyebrow mb-3">Logistics</p>
                <h3 class="az-title text-3xl font-semibold">Crescent Falcon Logistics</h3>
                <p class="mt-4 az-muted leading-7">
                    International transport, freight coordination and strategic goods movement operations.
                </p>
            </div>

            <div class="az-card p-6">
                <p class="az-eyebrow mb-3">Hospitality</p>
                <h3 class="az-title text-3xl font-semibold">Desert Crown Hospitality</h3>
                <p class="mt-4 az-muted leading-7">
                    Clubs, lounges, restaurants and premium leisure environments for select markets.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="az-section">
    <div class="az-container">
        <div class="az-card az-cta-card p-10 md:p-14">
            <p class="az-eyebrow mb-3">Opportunities</p>

            <h2 class="az-title text-4xl md:text-5xl font-bold max-w-4xl">
                Investing where prestige, culture and private capital intersect.
            </h2>

            <p class="mt-6 az-muted max-w-3xl text-lg leading-8">
                We partner with selected operators, developers and strategic ventures through
                a disciplined long-term investment framework built on reputation, discretion and scale.
            </p>

            <div class="mt-8">
                <a href="{{ route('contact') }}" class="az-btn az-btn-primary">
                    Contact Private Affairs
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
