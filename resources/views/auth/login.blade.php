@extends('layouts.app')

@section('content')
<section class="hero" style="min-height: 80vh;">
    <div class="container flex" style="justify-content: center;">
        <div class="card animate-fade-in-up" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-2">
                <h2>Selamat Datang</h2>
                <p>Silakan login ke akun Anda</p>
            </div>
            
            @if($errors->any())
                <div style="color: var(--primary); margin-bottom: 1rem; font-size: 0.9rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="label">Email / Nama Akun</label>
                    <input type="text" name="email" class="input" required autofocus value="{{ old('email') }}">
                </div>
                
                <div class="form-group">
                    <label class="label">Password</label>
                    <input type="password" name="password" class="input" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Login</button>
            </form>
            
            <div class="text-center mt-2">
                <p>Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: bold;">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
