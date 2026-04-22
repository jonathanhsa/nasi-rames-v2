@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-3 mb-4">
    <div class="card" style="border-left: 5px solid var(--primary);">
        <h3>Total Pesanan</h3>
        <p style="font-size: 2rem; font-weight: 800; color: var(--primary); margin: 0;">{{ $totalOrders }}</p>
    </div>
    <div class="card" style="border-left: 5px solid var(--secondary);">
        <h3>Pendapatan</h3>
        <p style="font-size: 2rem; font-weight: 800; color: var(--secondary); margin: 0;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="card" style="border-left: 5px solid var(--accent);">
        <h3>Total Pelanggan</h3>
        <p style="font-size: 2rem; font-weight: 800; color: var(--accent); margin: 0;">{{ $totalUsers }}</p>
    </div>
</div>

<div class="card">
    <h3>Pesanan Terbaru</h3>
    <div class="table-container mt-1">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span style="padding: 0.2rem 0.8rem; border-radius: var(--radius-full); font-size: 0.8rem; font-weight:bold; background: {{ $order->status == 'completed' ? 'rgba(0,210,211,0.1)' : 'rgba(255,165,0,0.1)' }}; color: {{ $order->status == 'completed' ? 'var(--accent)' : 'orange' }};">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
