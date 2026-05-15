<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden w-full max-w-[100vw]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Trinexa') }}</title>

    <!-- Font Premium untuk kesan "Wah" -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-mesh min-h-screen text-[var(--tx-text-dark)] antialiased selection:bg-[var(--tx-secondary)]/20 selection:text-gray-900 overflow-x-hidden w-full max-w-[100vw]">
    <div class="min-h-screen w-full max-w-[100vw] overflow-x-hidden relative">
        
        <!-- Navbar Component -->
        <x-navbar />

        <!-- Page Content -->
        <main class="pb-28 md:pb-12">
            {{ $slot }}
        </main>
        
    </div>

    <!-- AlpineJS needed for Dropdown if x-data is used -->
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <!-- Bottom Sheet Global Component -->
    @include('components.bottom-sheet')

    @stack('scripts')

    @auth
    {{-- ── Floating Truevera Button ─────────────────────────────── --}}
    <div id="truevera-float-container" 
         class="fixed z-50 transition-all duration-300"
         style="bottom: 110px; right: 24px;"
         x-data="trueveraFloat()"
         @mousedown="startDrag($event)"
         @touchstart="startDrag($event)"
         :style="`bottom: ${pos.y}px; right: ${pos.x}px; cursor: isDragging ? 'grabbing' : 'pointer'`">

        {{-- Popup Chat --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0 scale-95" 
             class="mb-4 w-[280px] sm:w-80 bg-white/80 backdrop-blur-xl rounded-[24px] shadow-2xl border border-white/60 overflow-hidden" 
             style="display:none">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-[var(--tx-secondary-light)] to-[var(--tx-tertiary-light)] px-4 py-3 flex items-center justify-between border-b border-white/50">
                <div class="flex items-center gap-2">
                    <div class="truevera-float">
                        <svg width="32" height="36" viewBox="0 0 140 160" fill="none">
                            <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="#F472B6"/>
                            <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.95"/>
                            <ellipse cx="55" cy="72" rx="8" ry="9" fill="#4A90D9"/>
                            <ellipse cx="85" cy="72" rx="8" ry="9" fill="#4A90D9"/>
                            <ellipse cx="55" cy="72" rx="4" ry="5" fill="#1E293B"/>
                            <ellipse cx="85" cy="72" rx="4" ry="5" fill="#1E293B"/>
                            <circle cx="56" cy="71" r="1.5" fill="white"/>
                            <circle cx="86" cy="71" r="1.5" fill="white"/>
                            <ellipse cx="42" cy="86" rx="7" ry="4" fill="#F472B6" opacity="0.4"/>
                            <ellipse cx="98" cy="86" rx="7" ry="4" fill="#F472B6" opacity="0.4"/>
                            <path d="M58 96 Q70 105 82 96" stroke="#1E293B" stroke-width="2" stroke-linecap="round" fill="none"/>
                            <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#bGFloat)"/>
                            <defs><linearGradient id="bGFloat" x1="45" y1="112" x2="95" y2="152"><stop stop-color="#4A90D9"/><stop offset="1" stop-color="#F472B6"/></linearGradient></defs>
                        </svg>
                    </div>
                    <div>
                        <p class="font-black text-[var(--tx-text-dark)] text-sm">Truevera</p>
                        <div class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-[9px] font-black text-green-500 uppercase tracking-widest">Online</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('konsultasi.chat.index') }}" class="text-[9px] font-black text-[var(--tx-primary)] uppercase tracking-widest hover:underline">Full →</a>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center rounded-full hover:bg-white/50 transition-colors">✕</button>
                </div>
            </div>

            {{-- Chat Messages --}}
            <div id="quick-chat-messages" class="h-64 overflow-y-auto p-4 space-y-3 scrollbar-hide bg-white/20">
                <div class="flex gap-2">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[var(--tx-secondary-light)] to-[var(--tx-tertiary-light)] flex items-center justify-center text-sm border border-white">🤖</div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-[16px] rounded-tl-[4px] px-3 py-2 text-xs font-bold text-[var(--tx-text-dark)] shadow-sm max-w-[85%] border border-white/60">
                        Halo {{ auth()->user()->name }}! Aku Truevera 🌸 Ada yang mau ditanyain soal kulitmu?
                    </div>
                </div>
            </div>

            {{-- Suggestions --}}
            <div class="px-3 py-2 border-t border-white/50 flex gap-2 overflow-x-auto scrollbar-hide bg-white/10">
                @foreach(['Kulit berminyak 😓', 'Rekomendasi serum ✨', 'Cara retinol 🌙'] as $sug)
                <button @click="quickSend('{{ $sug }}')" class="flex-shrink-0 text-[9px] font-black text-[var(--tx-secondary)] bg-white/60 border border-[var(--tx-secondary)]/20 px-3 py-1.5 rounded-full hover:bg-[var(--tx-secondary)] hover:text-white transition-all uppercase tracking-widest">{{ $sug }}</button>
                @endforeach
            </div>

            {{-- Input --}}
            <div class="p-3 border-t border-white/50 flex gap-2 bg-white/20">
                <input id="quick-input" type="text" placeholder="Tanya..." x-model="quickMsg"
                    @keypress.enter="quickSend()"
                    class="flex-1 text-xs font-bold bg-white/60 border border-white/60 rounded-full px-4 py-2 focus:outline-none focus:border-[var(--tx-secondary)] backdrop-blur-sm placeholder:text-gray-400">
                <button @click="quickSend()" class="w-9 h-9 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] rounded-full flex items-center justify-center text-white shadow-md hover:-translate-y-0.5 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </div>
        </div>

        {{-- Floating Button --}}
        <button @click="if(!isDragging) open = !open" 
                class="group relative flex items-center gap-2.5 bg-gradient-to-r from-[var(--tx-secondary)] to-[var(--tx-tertiary)] text-white font-black px-4 py-3 rounded-full shadow-xl shadow-[var(--tx-secondary)]/30 hover:shadow-[var(--tx-secondary)]/50 transition-all duration-300 border border-white/30 touch-none active:scale-95">
            <div class="truevera-float pointer-events-none">
                <svg width="24" height="28" viewBox="0 0 140 160" fill="none">
                    <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="rgba(255,255,255,0.8)"/>
                    <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.9"/>
                    <ellipse cx="55" cy="72" rx="8" ry="9" fill="#4A90D9"/>
                    <ellipse cx="85" cy="72" rx="8" ry="9" fill="#4A90D9"/>
                    <ellipse cx="55" cy="72" rx="4" ry="5" fill="#1E293B"/>
                    <ellipse cx="85" cy="72" rx="4" ry="5" fill="#1E293B"/>
                    <circle cx="56" cy="71" r="1.5" fill="white"/>
                    <circle cx="86" cy="71" r="1.5" fill="white"/>
                    <ellipse cx="42" cy="86" rx="7" ry="4" fill="rgba(244,114,182,0.4)"/>
                    <ellipse cx="98" cy="86" rx="7" ry="4" fill="rgba(244,114,182,0.4)"/>
                    <path d="M58 96 Q70 105 82 96" stroke="#1E293B" stroke-width="2" stroke-linecap="round" fill="none"/>
                    <rect x="45" y="112" width="50" height="40" rx="18" fill="rgba(255,255,255,0.3)"/>
                </svg>
            </div>
            <span class="text-xs sm:text-sm hidden sm:inline pointer-events-none">Tanya Truevera</span>
            <span class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white animate-pulse"></span>
        </button>
    </div>

    <script>
        function trueveraFloat() {
            return {
                open: false,
                quickMsg: '',
                history: [],
                loading: false,
                pos: { x: 24, y: window.innerWidth < 768 ? 110 : 24 },
                isDragging: false,
                dragStart: { x: 0, y: 0 },

                startDrag(e) {
                    this.isDragging = false;
                    const event = e.type.startsWith('touch') ? e.touches[0] : e;
                    this.dragStart = { x: event.clientX, y: event.clientY };
                    
                    const move = (me) => {
                        const mEvent = me.type.startsWith('touch') ? me.touches[0] : me;
                        const dx = this.dragStart.x - mEvent.clientX;
                        const dy = this.dragStart.y - mEvent.clientY;
                        if (Math.abs(dx) > 5 || Math.abs(dy) > 5) {
                            this.isDragging = true;
                            this.pos.x += dx;
                            this.pos.y += dy;
                            this.dragStart = { x: mEvent.clientX, y: mEvent.clientY };
                            // Boundary check
                            this.pos.x = Math.max(10, Math.min(window.innerWidth - 60, this.pos.x));
                            this.pos.y = Math.max(80, Math.min(window.innerHeight - 100, this.pos.y));
                        }
                    };

                    const end = () => {
                        document.removeEventListener('mousemove', move);
                        document.removeEventListener('mouseup', end);
                        document.removeEventListener('touchmove', move);
                        document.removeEventListener('touchend', end);
                        setTimeout(() => this.isDragging = false, 50);
                    };

                    document.addEventListener('mousemove', move);
                    document.addEventListener('mouseup', end);
                    document.addEventListener('touchmove', move, { passive: false });
                    document.addEventListener('touchend', end);
                },

                async quickSend(text) {
                    if (this.isDragging) return;
                    const msg = text || this.quickMsg.trim();
                    if (!msg || this.loading) return;
                    this.quickMsg = '';
                    this.loading = true;
                    this.appendQuick('user', msg);
                    this.history.push({ role: 'user', content: msg });
                    this.appendTyping();

                    try {
                        const res = await fetch('{{ route("konsultasi.chat.quick") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ message: msg, history: this.history }),
                        });
                        const data = await res.json();
                        document.getElementById('quick-typing')?.remove();
                        if (data.success) {
                            this.history.push({ role: 'assistant', content: data.message });
                            this.appendQuick('assistant', data.message);
                        }
                    } catch {
                        document.getElementById('quick-typing')?.remove();
                        this.appendQuick('assistant', 'Oops, coba lagi ya! 🌸');
                    }
                    this.loading = false;
                },

                appendQuick(role, content) {
                    const el = document.getElementById('quick-chat-messages');
                    const isUser = role === 'user';
                    const safeContent = content.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    el.insertAdjacentHTML('beforeend', isUser
                        ? `<div class="flex justify-end"><div class="bg-gradient-to-br from-[#4A90D9] to-[#9B8EC4] text-white rounded-[16px] rounded-tr-[4px] px-3 py-2 text-xs font-bold max-w-[85%]">${safeContent}</div></div>`
                        : `<div class="flex gap-2"><div class="w-6 h-6 rounded-full bg-[#FDF0F7] flex items-center justify-center text-sm border border-white shrink-0">🤖</div><div class="bg-white/80 rounded-[16px] rounded-tl-[4px] px-3 py-2 text-xs font-bold text-gray-700 shadow-sm max-w-[85%] border border-white/60">${safeContent}</div></div>`
                    );
                    el.scrollTop = el.scrollHeight;
                },

                appendTyping() {
                    const el = document.getElementById('quick-chat-messages');
                    el.insertAdjacentHTML('beforeend', `<div id="quick-typing" class="flex gap-2"><div class="w-6 h-6 rounded-full bg-[#FDF0F7] flex items-center justify-center text-sm border border-white">🤖</div><div class="bg-white/80 rounded-[16px] px-3 py-2 shadow-sm border border-white/60 flex gap-1 items-center"><span class="w-2 h-2 bg-[#F472B6] rounded-full animate-bounce" style="animation-delay:0s"></span><span class="w-2 h-2 bg-[#9B8EC4] rounded-full animate-bounce" style="animation-delay:0.15s"></span><span class="w-2 h-2 bg-[#4A90D9] rounded-full animate-bounce" style="animation-delay:0.3s"></span></div></div>`);
                    el.scrollTop = el.scrollHeight;
                },
            };
        }
    </script>
    @endauth
</body>
</html>
