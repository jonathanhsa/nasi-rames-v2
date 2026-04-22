@extends('layouts.app')

@section('content')
<section class="hero" style="min-height: auto; padding: 6rem 0 3rem 0; background: var(--surface);">
    <div class="container text-center animate-fade-in-up">
        <h2>Profil Pengguna</h2>
        <p>Kelola informasi akun Anda dan lihat riwayat pesanan.</p>
    </div>
</section>

<section class="p-2 mb-4">
    <div class="container grid" style="grid-template-columns: 1fr 2fr;">
        
        <!-- Profile Form -->
        <div class="card animate-fade-in-up">
            <h3>Informasi Akun</h3>
            
            @if(session('success'))
                <div style="color: var(--accent); margin-bottom: 1rem;">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div style="color: var(--primary); margin-bottom: 1rem; font-size: 0.9rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="mt-1">
                @csrf
                <div class="form-group">
                    <label class="label">Nama Lengkap</label>
                    <input type="text" name="name" class="input" value="{{ $user->name }}" required>
                </div>
                
                <div class="form-group">
                    <label class="label">Email Address (Read-only)</label>
                    <input type="email" class="input" value="{{ $user->email }}" readonly style="background: #e9ecef; color: #6c757d; cursor: not-allowed;">
                </div>
                
                <div class="form-group">
                    <label class="label">Nomor HP</label>
                    <input type="text" name="phone" class="input" value="{{ $user->phone }}" placeholder="08123456789">
                </div>
                
                <div class="form-group">
                    <label class="label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="input" placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
                
                <div class="form-group">
                    <label class="label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="input">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Update Profil</button>
            </form>
        </div>

        <!-- Order History -->
        <div class="card animate-fade-in-up delay-100">
            <div class="flex justify-between items-center">
                <h3>Riwayat Pesanan</h3>
            </div>
            
            <div class="table-container mt-2">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                <span style="padding: 0.2rem 0.8rem; border-radius: var(--radius-full); font-size: 0.8rem; font-weight:bold; background: {{ $order->status == 'completed' ? 'rgba(0,210,211,0.1)' : 'rgba(255,165,0,0.1)' }}; color: {{ $order->status == 'completed' ? 'var(--accent)' : 'orange' }};">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
