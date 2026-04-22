@extends('layouts.admin')

@section('title', 'Live Chat Support')

@section('content')
<div class="card">
    <p>Fitur live chat admin akan memunculkan percakapan dari user yang mengirim pesan.</p>
    <div class="grid grid-cols-3 mt-2">
        @forelse($messages as $userId => $userMessages)
        @php $customer = $userMessages->first()->user; @endphp
        <div class="card" style="border: 1px solid rgba(0,0,0,0.05); padding:1rem;">
            <h4 style="border-bottom: 1px solid #eee; padding-bottom:0.5rem; margin-bottom:0.5rem;">
                <i class="fa-solid fa-user"></i> {{ $customer->name ?? 'User' }}
            </h4>
            <div style="height: 200px; overflow-y:auto; display:flex; flex-direction:column; gap:0.5rem; margin-bottom:1rem;" id="chatbox-{{$userId}}">
                @foreach($userMessages as $msg)
                <div style="padding:0.5rem 0.8rem; border-radius:var(--radius-md); font-size:0.8rem; max-width:80%; align-self: {{ $msg->is_admin ? 'flex-end' : 'flex-start' }}; background: {{ $msg->is_admin ? 'var(--primary)' : 'var(--background)' }}; color: {{ $msg->is_admin ? 'white' : 'var(--text-main)' }};">
                    {{ $msg->message }}
                </div>
                @endforeach
            </div>
            
            <form x-data="{ message: '' }" @submit.prevent="
                fetch('/api/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ message: message, user_id: {{ $userId }} })
                }).then(() => {
                    window.location.reload();
                });
            " class="flex gap-1">
                <input type="text" x-model="message" class="input" style="padding:0.5rem;" placeholder="Balas...">
                <button type="submit" class="btn btn-secondary" style="padding:0.5rem 1rem;"><i class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>
        @empty
        <div style="grid-column: span 3; text-align:center; padding: 2rem;">Belum ada pesan masuk.</div>
        @endforelse
    </div>
</div>
@endsection
