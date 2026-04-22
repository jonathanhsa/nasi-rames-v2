@extends('layouts.admin')

@section('title', 'Manajemen Orders')

@section('content')
<div class="card">
    <h3>Semua Pesanan</h3>
    @if(session('success'))
        <div style="color: var(--accent); margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif
    <div class="table-container mt-1">
        <table>
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Detail Pesanan</th>
                    <th>Total Harga</th>
                    <th>Status & Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}<br><small>{{ $order->created_at->format('d M Y H:i') }}</small></td>
                    <td>{{ $order->user->name }}</td>
                    <td>
                        <ul style="padding-left:1rem; margin:0; font-size:0.9rem;">
                            @foreach($order->items as $item)
                                <li>{{ $item->quantity }}x {{ $item->menu->name ?? 'Menu Dihapus' }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td style="font-weight:bold;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center gap-1">
                            @csrf
                            <select name="status" class="input" style="padding:0.2rem 0.5rem; border-radius:var(--radius-md);">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="action-btn edit"><i class="fa-solid fa-check"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
