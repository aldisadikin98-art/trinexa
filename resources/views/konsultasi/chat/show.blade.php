<x-app-layout>
    <x-slot name="title">{{ $session->title }} | Aura Chat</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-180px)]">

            {{-- SIDEBAR --}}
            <aside class="lg:w-64 shrink-0 hidden lg:block">
                <div class="glass-card border border-white/50 h-full overflow-hidden flex flex-col">
                    <div class="p-4 border-b border-white/50 bg-white/20 flex items-center justify-between">
                        <h3 class="font-black text-[var(--tx-text-dark)] text-xs uppercase tracking-widest">Sesi Chat</h3>
                        <form action="{{ route('konsultasi.chat.session') }}" method="POST">
                            @csrf
                            <button class="bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full">+ Baru</button>
                        </form>
                    </div>
                    <div class="overflow-y-auto flex-1 divide-y divide-white/30 scrollbar-hide">
                        @foreach($sessions as $s)
                        <a href="{{ route('konsultasi.chat.show', $s) }}"
                           class="flex items-center gap-3 px-4 py-3 hover:bg-white/30 transition-colors {{ $s->id === $session->id ? 'bg-white/40 border-l-4 border-[var(--tx-secondary)]' : '' }}">
                            <span class="text-sm">💬</span>
                            <span class="font-bold text-[var(--tx-text-dark)] text-xs truncate flex-1">{{ $s->title }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            {{-- CHAT AREA --}}
            <div class="flex-1 glass-card border border-white/50 flex flex-col overflow-hidden" x-data="chatApp()">

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-white/50 bg-white/20 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="aura-float">
                            <svg width="40" height="46" viewBox="0 0 140 160" fill="none">
                                <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="#F472B6"/>
                                <rect x="22" y="55" width="12" height="20" rx="6" fill="#C4B5E8"/>
                                <rect x="106" y="55" width="12" height="20" rx="6" fill="#C4B5E8"/>
                                <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.95"/>
                                <ellipse cx="55" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                <ellipse cx="85" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                <ellipse cx="55" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                <ellipse cx="85" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                <circle cx="57" cy="70" r="2" fill="white"/>
                                <circle cx="87" cy="70" r="2" fill="white"/>
                                <ellipse cx="42" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                                <ellipse cx="98" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                                <path d="M57 97 Q70 108 83 97" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                                <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#bG2)"/>
                                <defs><linearGradient id="bG2" x1="45" y1="112" x2="95" y2="152"><stop stop-color="#4A90D9"/><stop offset="1" stop-color="#F472B6"/></linearGradient></defs>
                            </svg>
                        </div>
                        <div>
                            <p class="font-black text-[var(--tx-text-dark)] text-sm">Aura</p>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                <span class="text-[10px] font-black text-green-500 uppercase tracking-widest">Online</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('konsultasi.chat.index') }}" class="text-[10px] font-black text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] bg-white/40 px-3 py-1.5 rounded-full border border-white/60 transition-colors uppercase tracking-widest">← Kembali</a>
                        <button @click="confirmDelete()" class="text-[10px] font-black text-red-400 hover:text-red-600 bg-red-50/50 px-3 py-1.5 rounded-full border border-red-100 transition-colors uppercase tracking-widest">Hapus</button>
                    </div>
                </div>

                {{-- Messages --}}
                <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-6 scrollbar-hide bg-white/10">
                    @if($messages->isEmpty())
                    <div class="text-center py-16">
                        <p class="text-[var(--tx-text-muted)] font-bold text-sm">Halo! Ada yang bisa aku bantu hari ini? 🌸</p>
                    </div>
                    @endif
                    @foreach($messages as $msg)
                    @if($msg->role === 'user')
                    <div class="flex justify-end gap-3">
                        <div class="max-w-[75%] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-tertiary)] text-white rounded-[20px] rounded-tr-[6px] px-5 py-3.5 shadow-md">
                            <p class="text-sm font-bold leading-relaxed whitespace-pre-wrap">{{ $msg->content }}</p>
                            <p class="text-[9px] text-white/60 mt-1.5 text-right font-bold uppercase tracking-widest">{{ $msg->created_at->format('H:i') }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] flex items-center justify-center text-white font-black text-sm shrink-0 shadow-md border-2 border-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    @else
                    <div class="flex gap-3">
                        <div class="aura-float shrink-0">
                            <svg width="36" height="40" viewBox="0 0 140 160" fill="none">
                                <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.95"/>
                                <ellipse cx="55" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                <ellipse cx="85" cy="72" rx="9" ry="10" fill="#4A90D9"/>
                                <ellipse cx="55" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                <ellipse cx="85" cy="72" rx="5" ry="6" fill="#1E293B"/>
                                <circle cx="57" cy="70" r="2" fill="white"/>
                                <circle cx="87" cy="70" r="2" fill="white"/>
                                <ellipse cx="42" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                                <ellipse cx="98" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                                <path d="M57 97 Q70 108 83 97" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                                <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#bG3)"/>
                                <defs><linearGradient id="bG3" x1="45" y1="112" x2="95" y2="152"><stop stop-color="#4A90D9"/><stop offset="1" stop-color="#F472B6"/></linearGradient></defs>
                            </svg>
                        </div>
                        <div class="max-w-[75%]">
                            <div class="bg-white/70 backdrop-blur-sm border border-white/80 rounded-[20px] rounded-tl-[6px] px-5 py-3.5 shadow-md">
                                <p class="text-sm font-bold text-[var(--tx-text-dark)] leading-relaxed whitespace-pre-wrap">{{ $msg->content }}</p>
                                <p class="text-[9px] text-[var(--tx-text-muted)] mt-1.5 font-bold uppercase tracking-widest">{{ $msg->created_at->format('H:i') }}</p>
                            </div>
                            {{-- Product recommendations --}}
                            @if(!empty($msg->products))
                            <div class="flex gap-3 mt-3 overflow-x-auto scrollbar-hide pb-1">
                                @foreach($msg->products as $prod)
                                <a href="{{ route('shop.show', $prod['slug']) }}" class="shrink-0 bg-white/70 border border-white/80 rounded-[16px] p-3 flex items-center gap-3 w-52 hover:bg-white/90 transition-colors shadow-sm">
                                    <img src="{{ $prod['image'] }}" class="w-10 h-10 rounded-[10px] object-cover border border-white shadow-sm" onerror="this.src='https://placehold.co/40'">
                                    <div>
                                        <p class="text-[10px] font-black text-[var(--tx-text-dark)] line-clamp-2 leading-tight">{{ $prod['name'] }}</p>
                                        <p class="text-[10px] font-black text-[var(--tx-primary)] mt-0.5">Rp {{ number_format($prod['price'], 0, ',', '.') }}</p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach

                    {{-- Typing indicator (hidden by default) --}}
                    <div id="typingIndicator" class="flex gap-3 hidden">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[var(--tx-secondary-light)] to-[var(--tx-tertiary-light)] flex items-center justify-center border border-white shadow-sm">🤖</div>
                        <div class="bg-white/70 backdrop-blur-sm border border-white/80 rounded-[20px] rounded-tl-[6px] px-5 py-3.5 shadow-md">
                            <div class="flex gap-1.5 items-center h-5">
                                <span class="w-2.5 h-2.5 bg-[var(--tx-secondary)] rounded-full animate-bounce" style="animation-delay:0s"></span>
                                <span class="w-2.5 h-2.5 bg-[var(--tx-tertiary)] rounded-full animate-bounce" style="animation-delay:0.15s"></span>
                                <span class="w-2.5 h-2.5 bg-[var(--tx-primary)] rounded-full animate-bounce" style="animation-delay:0.3s"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick suggestions --}}
                <div class="px-4 py-3 border-t border-white/40 bg-white/10 flex gap-2 overflow-x-auto scrollbar-hide shrink-0">
                    @foreach(['Kulit berminyak & kusam 😓', 'Skincare untuk pemula ✨', 'Cara pakai retinol 🌙', 'Ingredients yang harus dihindari ⚠️'] as $sug)
                    <button @click="sendSuggestion('{{ $sug }}')"
                        class="flex-shrink-0 text-[10px] font-black text-[var(--tx-secondary)] bg-white/50 border border-[var(--tx-secondary)]/20 px-3 py-1.5 rounded-full hover:bg-[var(--tx-secondary)] hover:text-white transition-all uppercase tracking-widest">
                        {{ $sug }}
                    </button>
                    @endforeach
                </div>

                {{-- Input --}}
                <div class="p-4 border-t border-white/50 bg-white/20 shrink-0">
                    <div class="flex gap-3 items-end">
                        <div class="flex-1 bg-white/60 backdrop-blur-sm border border-white/80 rounded-[20px] px-5 py-3 shadow-inner">
                            <textarea id="messageInput" x-model="message" @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                                placeholder="Tanya Aura tentang kulitmu... (Enter untuk kirim, Shift+Enter untuk baris baru)"
                                rows="1" class="w-full bg-transparent focus:outline-none text-sm font-bold text-[var(--tx-text-dark)] placeholder:text-gray-400 resize-none"
                                style="max-height: 120px; overflow-y: auto;"></textarea>
                        </div>
                        <button @click="sendMessage()" :disabled="loading || !message.trim()"
                            class="w-12 h-12 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] rounded-[16px] flex items-center justify-center text-white shadow-lg hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed border border-white/30">
                            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function chatApp() {
            return {
                message: '',
                loading: false,
                init() {
                    this.scrollToBottom();
                    // Check for pre-filled message from session storage
                    const firstMsg = sessionStorage.getItem('aura_first_message');
                    if (firstMsg) {
                        sessionStorage.removeItem('aura_first_message');
                        this.message = firstMsg;
                        this.$nextTick(() => this.sendMessage());
                    }
                },
                scrollToBottom() {
                    const el = document.getElementById('chatMessages');
                    el.scrollTop = el.scrollHeight;
                },
                sendSuggestion(text) {
                    this.message = text;
                    this.sendMessage();
                },
                async sendMessage() {
                    const msg = this.message.trim();
                    if (!msg || this.loading) return;

                    this.message = '';
                    this.loading = true;

                    // Append user bubble immediately
                    this.appendBubble('user', msg);

                    // Show typing indicator
                    document.getElementById('typingIndicator').classList.remove('hidden');
                    this.scrollToBottom();

                    try {
                        const res = await fetch('{{ route("konsultasi.chat.send", $session) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ message: msg }),
                        });

                        const data = await res.json();

                        document.getElementById('typingIndicator').classList.add('hidden');

                        if (data.success) {
                            this.appendAuraBubble(data.message, data.products || []);
                        }
                    } catch (e) {
                        document.getElementById('typingIndicator').classList.add('hidden');
                        this.appendAuraBubble('Maaf, koneksi bermasalah. Coba lagi ya! 🌸', []);
                    }

                    this.loading = false;
                    this.scrollToBottom();
                },
                appendBubble(role, content) {
                    const el = document.getElementById('chatMessages');
                    const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    const initials = '{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}';
                    el.insertAdjacentHTML('beforeend', `
                        <div class="flex justify-end gap-3">
                            <div class="max-w-[75%] bg-gradient-to-br from-[#4A90D9] to-[#9B8EC4] text-white rounded-[20px] rounded-tr-[6px] px-5 py-3.5 shadow-md">
                                <p class="text-sm font-bold leading-relaxed whitespace-pre-wrap">${this.escapeHtml(content)}</p>
                                <p class="text-[9px] text-white/60 mt-1.5 text-right font-bold uppercase tracking-widest">${time}</p>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#4A90D9] to-[#F472B6] flex items-center justify-center text-white font-black text-sm shrink-0 shadow-md border-2 border-white">${initials}</div>
                        </div>
                    `);
                },
                appendAuraBubble(content, products) {
                    const el = document.getElementById('chatMessages');
                    const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    let productsHtml = '';
                    if (products && products.length) {
                        productsHtml = '<div class="flex gap-3 mt-3 overflow-x-auto pb-1">' +
                            products.map(p => `
                                <a href="/belanja/${p.slug}" class="shrink-0 bg-white/70 border border-white/80 rounded-[16px] p-3 flex items-center gap-3 w-52 hover:bg-white/90 transition-colors shadow-sm">
                                    <img src="${p.image}" class="w-10 h-10 rounded-[10px] object-cover" onerror="this.src='https://placehold.co/40'">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-800 line-clamp-2 leading-tight">${p.name}</p>
                                        <p class="text-[10px] font-black text-[#4A90D9] mt-0.5">Rp ${Number(p.price).toLocaleString('id-ID')}</p>
                                    </div>
                                </a>`
                            ).join('') + '</div>';
                    }
                    el.insertAdjacentHTML('beforeend', `
                        <div class="flex gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#FDF0F7] to-[#F3F0FB] flex items-center justify-center border border-white shadow-sm shrink-0 text-lg">🤖</div>
                            <div class="max-w-[75%]">
                                <div class="bg-white/70 backdrop-blur-sm border border-white/80 rounded-[20px] rounded-tl-[6px] px-5 py-3.5 shadow-md">
                                    <p class="text-sm font-bold text-gray-800 leading-relaxed whitespace-pre-wrap">${this.escapeHtml(content)}</p>
                                    <p class="text-[9px] text-gray-400 mt-1.5 font-bold uppercase tracking-widest">${time}</p>
                                </div>
                                ${productsHtml}
                            </div>
                        </div>
                    `);
                },
                escapeHtml(text) {
                    return text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
                },
                async confirmDelete() {
                    if (!confirm('Hapus sesi chat ini?')) return;
                    await fetch('{{ route("konsultasi.chat.destroy", $session) }}', {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                    window.location.href = '{{ route("konsultasi.chat.index") }}';
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
