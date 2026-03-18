@extends('layouts.public', ['title' => 'About'])

@section('content')

<section class="az-section">
    <div class="az-container">

        <p class="az-eyebrow mb-3">Al-Zafir Capital</p>

        <h1 class="az-title text-5xl mb-6">
            About
        </h1>

        <div class="max-w-5xl space-y-6">
            <p class="az-muted text-lg leading-8">
                Al-Zafir Capital is a private investment holding headquartered in Dubai, built on the principles of power, discretion, and strategic capital. We invest with a long-term vision, partnering with ventures that shape industries and create lasting value.
            </p>

            <p class="az-muted text-lg leading-8">
                Our focus spans key sectors including hospitality, technology, logistics, entertainment, and cultural initiatives. Through disciplined investment strategies and a deep understanding of global markets, we support projects that combine innovation, operational excellence, and sustainable growth.
            </p>

            <p class="az-muted text-lg leading-8">
                At Al-Zafir Capital, we believe capital is more than funding — it is a catalyst for transformation. By working closely with visionary founders, operators, and institutions, we help develop businesses and experiences that influence economies, communities, and culture.
            </p>

            <p class="az-muted text-lg leading-8">
                Driven by ambition and guided by discretion, Al-Zafir Capital stands as a trusted partner for strategic investment and long-term value creation.
            </p>
        </div>

    </div>
</section>

<section class="az-section pt-4">
    <div class="az-container">
        <div class="grid md:grid-cols-3 gap-6">

            <div class="az-card p-6">
                <p class="az-eyebrow mb-3">Vision</p>
                <h2 class="az-title text-3xl font-semibold">Long-term value</h2>
                <p class="mt-4 az-muted leading-7">
                    We invest in ventures with strong fundamentals, scalable potential, and enduring strategic relevance.
                </p>
            </div>

            <div class="az-card p-6">
                <p class="az-eyebrow mb-3">Approach</p>
                <h2 class="az-title text-3xl font-semibold">Strategic partnership</h2>
                <p class="mt-4 az-muted leading-7">
                    We work alongside founders, operators, and institutions to strengthen execution and accelerate growth.
                </p>
            </div>

            <div class="az-card p-6">
                <p class="az-eyebrow mb-3">Principles</p>
                <h2 class="az-title text-3xl font-semibold">Power & discretion</h2>
                <p class="mt-4 az-muted leading-7">
                    Our identity is built on disciplined capital allocation, selective opportunities, and absolute discretion.
                </p>
            </div>

        </div>
    </div>
</section>

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
@endsection
