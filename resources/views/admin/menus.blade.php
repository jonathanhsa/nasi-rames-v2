@extends('layouts.admin')

@section('title', 'Manajemen Menu')

@section('content')
<div class="mb-4">
    
    <div class="card">
        <h3>Tambah Menu Baru</h3>
        
        @if(session('success'))
            <div style="color: var(--accent); margin-bottom: 1rem;">{{ session('success') }}</div>
        @endif
        
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="mt-1">
            @csrf
            <div class="form-group">
                <label class="label">Nama Menu</label>
                <input type="text" name="name" class="input" required>
            </div>
            
            <div class="form-group">
                <label class="label">Deskripsi</label>
                <input type="text" name="description" class="input">
            </div>
            
            <div class="form-group">
                <label class="label">Harga (Rp)</label>
                <input type="number" name="price" class="input" required>
            </div>

            <div class="form-group">
                <label class="label">Stok</label>
                <input type="number" name="stock" class="input" value="0" required>
            </div>

            <div class="form-group">
                <label class="label">Kategori</label>
                <input type="text" name="category" class="input" placeholder="Makanan / Minuman">
            </div>
            
            <div class="form-group">
                <label class="label">Gambar (URL)</label>
                <input type="text" name="image_url" class="input" placeholder="https://...">
                <small style="color:var(--text-muted)">Atau upload gambar lokal:</small>
                <input type="file" name="image" class="input" style="padding:0.5rem; margin-top:0.5rem;">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Menu</button>
        </form>
    </div>

    <div class="card">
        <h3>Daftar Menu</h3>
        <div class="table-container mt-1">
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                    <tr>
                        <td><img src="{{ $menu->image_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100' }}" style="width:50px; height:50px; border-radius:8px; object-fit:cover;"></td>
                        <td>
                            <strong>{{ $menu->name }}</strong><br>
                            <small>{{ $menu->category }}</small>
                        </td>
                        <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td>{{ $menu->stock }}</td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.menus.edit', $menu->id) }}" class="action-btn edit"><i class="fa-solid fa-edit"></i></a>
                                <form action="{{ route('admin.menus.delete', $menu->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
