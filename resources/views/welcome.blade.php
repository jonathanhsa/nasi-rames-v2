@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-text animate-fade-in-up">
            <h1>Rasakan Kenikmatan <span style="color: var(--primary);">Nasi Rames</span> Terbaik</h1>
            <p class="mt-1" style="font-size: 1.2rem;">Kantin Ibu Ida hadir dengan berbagai macam menu nasi rames yang lezat, higienis, dan terjangkau.</p>
            <div class="mt-2 flex gap-1">
                <a href="{{ route('menu') }}" class="btn btn-primary">Lihat Menu & Pesan</a>
                <a href="#about" class="btn btn-outline">Tentang Kami</a>
            </div>
        </div>
        <div class="hero-image animate-fade-in-up delay-200 animate-float">
            <img src="https://images.unsplash.com/photo-1615486171448-4fd6a10051cd?q=80&w=1000&auto=format&fit=crop" alt="Nasi Rames Ibu Ida">
        </div>
    </div>
</section>

<section id="about" class="p-2 mt-4 mb-4" style="background: var(--surface);">
    <div class="container text-center animate-fade-in-up">
        <h2>Tentang Kantin Ibu Ida</h2>
        <p style="max-width: 600px; margin: 1rem auto; font-size: 1.1rem;">Berawal dari resep keluarga turun temurun, Kantin Ibu Ida menyajikan nasi rames otentik dengan bumbu pilihan. Kebersihan dan kepuasan pelanggan adalah prioritas utama kami.</p>
    </div>
</section>
@endsection
