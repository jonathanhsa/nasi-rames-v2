@extends('layouts.app')

@section('content')
<!-- Duitku Payment Integration -->

<section class="hero" style="min-height: auto; padding: 6rem 0 3rem 0; background: var(--surface);">
    <div class="container text-center animate-fade-in-up">
        <h2>Keranjang Belanja</h2>
        <p>Review pesanan Anda sebelum melakukan pembayaran.</p>
    </div>
</section>

<section class="p-2 mb-4">
    <div class="container">
        
        @if(session('success'))
            <div class="card mb-2" style="background: rgba(0, 210, 211, 0.1); color: var(--accent); border-color: var(--accent);">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="card mb-2" style="background: rgba(255, 51, 102, 0.1); color: var(--primary); border-color: var(--primary);">
                {{ $errors->first() }}
            </div>
        @endif

        @if($order && $order->items->count() > 0)
        <div class="grid" style="grid-template-columns: 2fr 1fr;" x-data="{ 
            totalPrice: '{{ number_format($order->total_price, 0, ',', '.') }}',
            paymentMethod: 'SP',
            async updateQuantity(itemId, action) {
                const response = await fetch(`/cart/update/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ action: action })
                });
                const data = await response.json();
                if (data.success) {
                    $store.cart.updateCount(data.cartCount);
                    this.totalPrice = data.totalPrice;
                    if (data.itemQuantity === 0) {
                        document.getElementById(`cart-item-${itemId}`).remove();
                        if (data.cartCount === 0) location.reload();
                    } else {
                        document.getElementById(`item-qty-${itemId}`).innerText = data.itemQuantity;
                    }
                } else {
                    alert(data.message || 'Gagal mengubah jumlah');
                }
            },
            async removeItem(itemId) {
                if (!confirm('Hapus item ini?')) return;
                const response = await fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    $store.cart.updateCount(data.cartCount);
                    this.totalPrice = data.totalPrice;
                    document.getElementById(`cart-item-${itemId}`).remove();
                    if (data.cartCount === 0) location.reload();
                }
            },
            isProcessing: false,
            async checkout(paymentMethod) {
                this.isProcessing = true;
                try {
                    const response = await fetch(`{{ route('order.checkout') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ paymentMethod: paymentMethod })
                    });
                    const data = await response.json();
                    
                    if (data.success && data.payment_url) {
                        window.location.href = data.payment_url;
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                        this.isProcessing = false;
                    }
                } catch (e) {
                    alert('Gagal menghubungi server');
                    this.isProcessing = false;
                }
            }
        }">
            <!-- Cart Items -->
            <div class="card animate-fade-in-up">
                <h3>Daftar Item</h3>
                <div class="mt-2">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between p-1" id="cart-item-{{ $item->id }}" style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div class="flex items-center gap-1">
                            <img src="{{ $item->menu->image_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100' }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit:cover;">
                            <div>
                                <h4 style="margin: 0;">{{ $item->menu->name ?? 'Menu dihapus' }}</h4>
                                <div style="color: var(--primary); font-weight: bold;">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-1">
                            <button @click="updateQuantity({{ $item->id }}, 'decrease')" class="btn btn-outline" style="padding: 0.2rem 0.6rem;">-</button>
                            
                            <span id="item-qty-{{ $item->id }}" style="font-weight: bold; width: 30px; text-align: center;">{{ $item->quantity }}</span>
                            
                            <button @click="updateQuantity({{ $item->id }}, 'increase')" class="btn btn-outline" style="padding: 0.2rem 0.6rem;">+</button>

                            <button @click="removeItem({{ $item->id }})" class="action-btn delete" style="margin-left: 1rem;"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card animate-fade-in-up delay-100" style="align-self: start;">
                <h3>Ringkasan Pembayaran</h3>
                
                <div class="flex justify-between mt-2 mb-1">
                    <span style="color: var(--text-muted);">Total Harga:</span>
                    <span style="font-weight: bold;">Rp <span x-text="totalPrice"></span></span>
                </div>

                <div class="mb-2">
                    <label class="label">Pilih Metode Pembayaran</label>
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-top: 0.5rem;">
                        <label class="card" :style="paymentMethod === 'SP' ? 'border-color: var(--accent); background: rgba(0,210,211,0.05);' : 'cursor: pointer;'" @click="paymentMethod = 'SP'">
                            <input type="radio" name="payment_method" value="SP" x-model="paymentMethod" style="display: none;">
                            <div class="text-center">
                                <i class="fa-solid fa-qrcode" style="font-size: 1.5rem; color: var(--accent);"></i>
                                <div style="font-size: 0.8rem; font-weight: bold; margin-top: 0.5rem;">QRIS</div>
                            </div>
                        </label>
                        <label class="card" :style="paymentMethod === 'M1' ? 'border-color: var(--accent); background: rgba(0,210,211,0.05);' : 'cursor: pointer;'" @click="paymentMethod = 'M1'">
                            <input type="radio" name="payment_method" value="M1" x-model="paymentMethod" style="display: none;">
                            <div class="text-center">
                                <i class="fa-solid fa-building-columns" style="font-size: 1.5rem; color: var(--accent);"></i>
                                <div style="font-size: 0.8rem; font-weight: bold; margin-top: 0.5rem;">Virtual Account</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="label">Informasi Pembayaran</label>
                    <p class="text-sm mt-1" style="color: var(--text-muted);">
                        Pembayaran akan diproses melalui gateway aman Duitku.
                    </p>
                </div>

                <div class="mt-2">
                    <button @click="checkout(paymentMethod)" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 1rem;" x-bind:disabled="isProcessing">
                        <span x-show="!isProcessing"><i class="fa-solid fa-lock"></i> Bayar Sekarang</span>
                        <span x-show="isProcessing" style="display: none;"><i class="fa-solid fa-spinner fa-spin"></i> Memproses...</span>
                    </button>
                    <form id="payment-success-form" action="{{ route('payment.success') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
                <div class="text-center mt-1">
                    <small style="color: var(--text-muted);"><i class="fa-solid fa-shield-halved"></i> Pembayaran aman didukung oleh <strong>Duitku</strong></small>
                </div>
            </div>
        </div>
        @else
        <div class="card text-center animate-fade-in-up" style="padding: 4rem 2rem;">
            <i class="fa-solid fa-cart-shopping" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h3>Keranjang masih kosong</h3>
            <p>Yuk, lihat-lihat menu lezat kami!</p>
            <a href="{{ route('menu') }}" class="btn btn-primary mt-2">Pesan Sekarang</a>
        </div>
        @endif

    </div>
</section>
@endsection
