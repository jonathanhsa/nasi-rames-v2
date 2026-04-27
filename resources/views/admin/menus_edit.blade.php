@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="flex justify-between items-center mb-2">
        <h3>Edit: {{ $menu->name }}</h3>
        <a href="{{ route('admin.menus') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Kembali</a>
    </div>
    
    @if(session('success'))
        <div style="color: var(--accent); margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif
    
    @if($errors->any())
        <div style="color: var(--primary); margin-bottom: 1rem;">{{ $errors->first() }}</div>
    @endif
    
    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label class="label">Nama Menu</label>
            <input type="text" name="name" class="input" value="{{ old('name', $menu->name) }}" required>
        </div>
        
        <div class="form-group">
            <label class="label">Deskripsi</label>
            <input type="text" name="description" class="input" value="{{ old('description', $menu->description) }}">
        </div>
        
        <div class="form-group">
            <label class="label">Harga (Rp)</label>
            <input type="number" name="price" class="input" value="{{ old('price', $menu->price) }}" required>
        </div>

        <div class="form-group">
            <label class="label">Stok</label>
            <input type="number" name="stock" class="input" value="{{ old('stock', $menu->stock) }}" required>
        </div>

        <div class="form-group">
            <label class="label">Kategori</label>
            <input type="text" name="category" class="input" value="{{ old('category', $menu->category) }}">
        </div>
        
        <div class="form-group">
            <label class="label">Gambar Saat Ini</label>
            @if($menu->image_url)
                <div style="margin-bottom: 0.5rem;">
                    <img src="{{ $menu->image_url }}" alt="Preview" style="max-width: 200px; border-radius: var(--radius-md);">
                </div>
            @endif
            <label class="label mt-1">Ubah Gambar (URL)</label>
            <input type="text" name="image_url" class="input" value="{{ old('image_url', $menu->image_url) }}" placeholder="https://...">
            <small style="color:var(--text-muted)">Atau upload gambar lokal:</small>
            <input type="file" name="image" class="input" style="padding:0.5rem; margin-top:0.5rem;">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Update Menu</button>
    </form>
</div>
@endsection
