<x-app-layout>
    <x-slot name="title">Tanya Aura — Chat AI | Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-6">

            {{-- SIDEBAR: Riwayat Sesi --}}
            <aside class="lg:w-72 shrink-0">
                <div class="glass-card border border-white/50 sticky top-24 overflow-hidden">
                    <div class="p-5 border-b border-white/50 bg-white/20 flex items-center justify-between">
                        <h3 class="font-black text-[var(--tx-text-dark)] text-sm uppercase tracking-widest">💬 Riwayat Chat</h3>
                        <form action="{{ route('konsultasi.chat.session') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-md hover:-translate-y-0.5 transition-transform">
                                + Baru
                            </button>
                        </form>
                    </div>
                    <div class="overflow-y-auto max-h-[60vh] divide-y divide-white/30">
                        @forelse($sessions as $session)
                        <a href="{{ route('konsultasi.chat.show', $session) }}" class="flex items-start gap-3 px-5 py-4 hover:bg-white/30 transition-colors group">
                            <div class="w-8 h-8 rounded-[10px] bg-gradient-to-br from-[var(--tx-secondary-light)] to-[var(--tx-tertiary-light)] flex items-center justify-center text-sm shrink-0 border border-white shadow-sm">
                                💬
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-[var(--tx-text-dark)] text-xs truncate">{{ $session->title }}</p>
                                <p class="text-[9px] font-bold text-[var(--tx-text-muted)] mt-0.5 uppercase tracking-widest">{{ $session->messages_count }} pesan · {{ $session->updated_at->diffForHumans() }}</p>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-10 px-5">
                            <div class="text-4xl mb-3 opacity-40">💬</div>
                            <p class="text-xs font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Belum ada chat</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </aside>

            {{-- MAIN: Hero Start --}}
            <div class="flex-1">
                <div class="glass-card border border-white/50 flex flex-col items-center justify-center py-24 px-8 text-center">
                    <div class="aura-float mb-6">
                        <svg width="100" height="115" viewBox="0 0 140 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="#F472B6" opacity="0.9"/>
                            <circle cx="55" cy="20" r="4" fill="#9B8EC4"/>
                            <circle cx="70" cy="32" r="4" fill="#F472B6"/>
                            <circle cx="85" cy="20" r="4" fill="#9B8EC4"/>
                            <rect x="22" y="55" width="12" height="20" rx="6" fill="#C4B5E8"/>
                            <rect x="106" y="55" width="12" height="20" rx="6" fill="#C4B5E8"/>
                            <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.95"/>
                            <ellipse cx="55" cy="72" rx="10" ry="11" fill="#4A90D9"/>
                            <ellipse cx="85" cy="72" rx="10" ry="11" fill="#4A90D9"/>
                            <ellipse cx="55" cy="72" rx="6" ry="7" fill="#1E293B"/>
                            <ellipse cx="85" cy="72" rx="6" ry="7" fill="#1E293B"/>
                            <circle cx="57" cy="70" r="2" fill="white"/>
                            <circle cx="87" cy="70" r="2" fill="white"/>
                            <ellipse cx="42" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                            <ellipse cx="98" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                            <path d="M57 97 Q70 108 83 97" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                            <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#bodyGradIdx)"/>
                            <defs>
                                <linearGradient id="bodyGradIdx" x1="45" y1="112" x2="95" y2="152" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#4A90D9"/><stop offset="1" stop-color="#F472B6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-[var(--tx-text-dark)] mb-3">Ayo ngobrol sama Aura! 🌸</h2>
                    <p class="text-[var(--tx-text-muted)] font-bold text-sm mb-8 max-w-md">Tanya soal rutinitas perawatan kulit, bahan aktif, atau masalah kulit kamu. Aura siap bantu!</p>

                    <div class="flex flex-wrap justify-center gap-3 mb-8">
                        @foreach(['Kulit aku berminyak banget 😓', 'Rekomendasi serum untuk pemula ✨', 'Cara pakai retinol yang benar 🌙', 'Bedanya AHA, BHA, PHA apa?'] as $q)
                        <button onclick="startChatWithQuestion('{{ $q }}')"
                            class="text-sm font-black text-[var(--tx-secondary)] bg-white/50 border border-[var(--tx-secondary)]/20 px-4 py-2 rounded-full hover:bg-[var(--tx-secondary)] hover:text-white transition-all shadow-sm hover:-translate-y-0.5">
                            {{ $q }}
                        </button>
                        @endforeach
                    </div>

                    <form action="{{ route('konsultasi.chat.session') }}" method="POST" id="startChatForm">
                        @csrf
                        <button type="submit" class="btn-gradient shadow-xl gap-2 px-8 py-4">
                            💬 Mulai Chat Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function startChatWithQuestion(q) {
            sessionStorage.setItem('aura_first_message', q);
            document.getElementById('startChatForm').submit();
        }
    </script>
    @endpush
</x-app-layout>
