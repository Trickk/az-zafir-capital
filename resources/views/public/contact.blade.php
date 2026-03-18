@extends('layouts.public', ['title' => 'Contact'])

@section('content')

<section class="az-section">
<div class="az-container w-full relative z-10">

<p class="az-eyebrow mb-3">Corporate Contact</p>

<h1 class="az-title text-5xl mb-8">
Contact
</h1>

<div class="grid md:grid-cols-2 gap-10">

<div>

<p class="az-muted text-lg leading-8">
For investment opportunities, partnerships or corporate inquiries
please contact our international relations team.
</p>

<div class="mt-6 space-y-3 az-muted">

<p>
<strong>Headquarters</strong><br>
Abu Dhabi Global Financial District
</p>

<p>
<strong>Email</strong><br>
contact@alzafircapital.com
</p>

</div>

</div>

<div class="az-card p-6">

<form class="space-y-4">

<input type="text" placeholder="Name"
class="w-full p-3 bg-black border border-gray-700 rounded">

<input type="email" placeholder="Email"
class="w-full p-3 bg-black border border-gray-700 rounded">

<textarea placeholder="Message"
class="w-full p-3 bg-black border border-gray-700 rounded"></textarea>

<button class="az-btn az-btn-primary w-full">
Send Message
</button>

</form>

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
