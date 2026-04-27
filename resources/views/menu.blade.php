@extends('layouts.app')

@section('content')
<section class="hero" style="min-height: auto; padding: 6rem 0 3rem 0; background: var(--surface);">
    <div class="container text-center animate-fade-in-up">
        <h2>Menu Kantin Ibu Ida</h2>
        <p>Pilih hidangan favoritmu!</p>
    </div>
</section>

<section class="p-2 mb-4" x-data="{ filter: 'Semua' }">
    <div class="container">
        
        <!-- Filter Buttons -->
        <div class="flex items-center justify-center gap-1 mb-4 animate-fade-in-up">
            <button @click="filter = 'Semua'" :class="filter === 'Semua' ? 'btn-primary' : 'btn-outline'" class="btn" style="padding: 0.5rem 1.5rem;">Semua</button>
            <button @click="filter = 'Makanan'" :class="filter === 'Makanan' ? 'btn-primary' : 'btn-outline'" class="btn" style="padding: 0.5rem 1.5rem;">Makanan</button>
            <button @click="filter = 'Minuman'" :class="filter === 'Minuman' ? 'btn-primary' : 'btn-outline'" class="btn" style="padding: 0.5rem 1.5rem;">Minuman</button>
        </div>

        @if(session('success'))
            <div class="card mb-2" style="background: rgba(0, 210, 211, 0.1); color: var(--accent); border-color: var(--accent);">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-4 animate-fade-in-up delay-100">
            @foreach($menus as $menu)
            <div class="card menu-card" x-show="filter === 'Semua' || filter === '{{ $menu->category }}'" style="display: flex; flex-direction: column; height: 100%;">
                <img src="{{ $menu->image_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop' }}" class="menu-img" alt="{{ $menu->name }}">
                <div class="menu-details" style="flex-grow: 1; display: flex; flex-direction: column;">
                    <h3>{{ $menu->name }}</h3>
                    <p style="font-size: 0.9rem;">{{ $menu->description }}</p>
                    
                    <!-- Bottom section forced to bottom -->
                    <div style="margin-top: auto;">
                        <div class="menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                        <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 0.5rem;">Sisa Stok: {{ $menu->stock }}</div>
                        
                        @auth
                        <div class="mt-1" x-data="{ 
                            quantity: 1,
                            async addToCart() {
                                if (this.quantity > {{ $menu->stock }}) {
                                    alert('Stok tidak mencukupi! Sisa: {{ $menu->stock }}');
                                    return;
                                }
                                const response = await fetch('{{ route('order.add') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        menu_id: {{ $menu->id }},
                                        quantity: this.quantity
                                    })
                                });
                                const data = await response.json();
                                if (data.success) {
                                    $store.cart.updateCount(data.cartCount);
                                    alert('Berhasil ditambahkan ke keranjang!');
                                } else {
                                    alert(data.message || 'Gagal menambahkan pesanan');
                                }
                            }
                        }">
                            @if($menu->stock > 0)
                            <div class="flex gap-1">
                                <input type="number" x-model="quantity" min="1" max="{{ $menu->stock }}" class="input" style="width:80px; padding: 0.5rem;">
                                <button @click="addToCart()" class="btn btn-primary" style="flex-grow:1; padding: 0.5rem;"><i class="fa-solid fa-cart-plus"></i> Tambah</button>
                            </div>
                            @else
                            <button class="btn btn-outline" style="width:100%; padding: 0.5rem;" disabled>Stok Habis</button>
                            @endif
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-outline" style="width:100%; padding: 0.5rem;">Login untuk Pesan</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
</section>
@endsection
