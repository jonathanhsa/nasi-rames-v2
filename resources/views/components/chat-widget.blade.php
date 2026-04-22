<div class="chat-widget" x-data="{ open: false, message: '', messages: [] }" x-init="
    setInterval(() => {
        if(open) {
            fetch('/api/chat')
                .then(res => res.json())
                .then(data => { messages = data; });
        }
    }, 3000);
">
    <div class="chat-toggle" @click="
        open = !open; 
        if(open) {
            fetch('/api/chat')
                .then(res => res.json())
                .then(data => { 
                    messages = data; 
                    setTimeout(() => { $refs.chatbox.scrollTop = $refs.chatbox.scrollHeight; }, 100);
                });
        }
    ">
        <i class="fa-solid fa-comment-dots"></i>
    </div>
    
    <div class="chat-window" :class="{ 'active': open }">
        <div class="chat-header">
            <span>Live Chat Admin</span>
            <i class="fa-solid fa-times" style="cursor:pointer" @click="open = false"></i>
        </div>
        <div class="chat-messages" x-ref="chatbox">
            <template x-for="msg in messages" :key="msg.id">
                <div class="chat-msg" :class="msg.is_admin ? 'admin' : 'user'">
                    <span x-text="msg.message"></span>
                </div>
            </template>
            <div x-show="messages.length === 0" style="text-align:center; color:var(--text-muted); margin-top:2rem;">
                Belum ada pesan. Silakan tanya kami!
            </div>
        </div>
        <div class="chat-input">
            <input type="text" x-model="message" placeholder="Ketik pesan..." @keyup.enter="
                if(message.trim() !== '') {
                    fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: message })
                    }).then(() => {
                        messages.push({ id: Date.now(), message: message, is_admin: 0 });
                        message = '';
                        setTimeout(() => { $refs.chatbox.scrollTop = $refs.chatbox.scrollHeight; }, 100);
                    });
                }
            ">
            <button @click="
                if(message.trim() !== '') {
                    fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: message })
                    }).then(() => {
                        messages.push({ id: Date.now(), message: message, is_admin: 0 });
                        message = '';
                        setTimeout(() => { $refs.chatbox.scrollTop = $refs.chatbox.scrollHeight; }, 100);
                    });
                }
            "><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>
