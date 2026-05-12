<x-app-layout>
    <x-slot name="title">Konsultasi AI Truevera | Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- HERO BANNER --}}
        <div class="glass-card relative overflow-hidden border border-white/50 mb-10 shadow-xl">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background: radial-gradient(circle at 20% 50%, var(--tx-secondary) 0%, transparent 60%), radial-gradient(circle at 80% 50%, var(--tx-primary) 0%, transparent 60%);"></div>
            <div class="relative p-8 md:p-12 flex flex-col md:flex-row items-center gap-8">
                {{-- Truevera Mascot --}}
                <div class="shrink-0 truevera-float">
                    <svg width="140" height="160" viewBox="0 0 140 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Crown -->
                        <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="#F472B6" opacity="0.9"/>
                        <circle cx="55" cy="20" r="4" fill="#9B8EC4"/>
                        <circle cx="70" cy="32" r="4" fill="#F472B6"/>
                        <circle cx="85" cy="20" r="4" fill="#9B8EC4"/>
                        <!-- Ears (robot) -->
                        <rect x="22" y="55" width="12" height="20" rx="6" fill="#C4B5E8"/>
                        <rect x="106" y="55" width="12" height="20" rx="6" fill="#C4B5E8"/>
                        <rect x="25" y="59" width="6" height="12" rx="3" fill="#9B8EC4"/>
                        <rect x="109" y="59" width="6" height="12" rx="3" fill="#9B8EC4"/>
                        <!-- Head -->
                        <rect x="30" y="40" width="80" height="75" rx="28" fill="white" opacity="0.95"/>
                        <rect x="30" y="40" width="80" height="75" rx="28" fill="url(#headGrad)" opacity="0.3"/>
                        <defs>
                            <linearGradient id="headGrad" x1="30" y1="40" x2="110" y2="115" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#EBF4FF"/>
                                <stop offset="1" stop-color="#FDF0F7"/>
                            </linearGradient>
                        </defs>
                        <!-- Eyes -->
                        <ellipse cx="55" cy="72" rx="10" ry="11" fill="#4A90D9"/>
                        <ellipse cx="85" cy="72" rx="10" ry="11" fill="#4A90D9"/>
                        <ellipse cx="55" cy="72" rx="6" ry="7" fill="#1E293B" class="truevera-blink" style="transform-origin: 55px 72px;"/>
                        <ellipse cx="85" cy="72" rx="6" ry="7" fill="#1E293B" class="truevera-blink" style="transform-origin: 85px 72px;"/>
                        <circle cx="57" cy="70" r="2" fill="white"/>
                        <circle cx="87" cy="70" r="2" fill="white"/>
                        <!-- Blush -->
                        <ellipse cx="42" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                        <ellipse cx="98" cy="88" rx="8" ry="5" fill="#F472B6" opacity="0.35"/>
                        <!-- Smile -->
                        <path d="M57 97 Q70 108 83 97" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                        <!-- Body -->
                        <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#bodyGrad)"/>
                        <defs>
                            <linearGradient id="bodyGrad" x1="45" y1="112" x2="95" y2="152" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#4A90D9"/>
                                <stop offset="1" stop-color="#F472B6"/>
                            </linearGradient>
                        </defs>
                        <!-- Chest badge -->
                        <rect x="58" y="122" width="24" height="14" rx="7" fill="white" opacity="0.3"/>
                        <text x="70" y="132" text-anchor="middle" font-size="8" fill="white" font-weight="bold">AI</text>
                        <!-- Arms -->
                        <rect x="25" y="115" width="22" height="10" rx="5" fill="#4A90D9" transform="rotate(-15 36 120)"/>
                        <rect x="93" y="115" width="22" height="10" rx="5" fill="#F472B6" transform="rotate(15 104 120)"/>
                    </svg>
                </div>

                {{-- Text --}}
                <div class="flex-1 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 bg-white/30 backdrop-blur-sm border border-white/50 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest text-[var(--tx-text-dark)] mb-4">
                        🤖 AI SKINCARE CONSULTANT
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-[var(--tx-text-dark)] mb-3 leading-tight">
                        Hai, aku <span class="text-gradient">Truevera</span>! 🌸
                    </h1>
                    <p class="text-[var(--tx-text-muted)] font-bold text-base mb-6 max-w-lg leading-relaxed">
                        Konsultan skincare AI personal kamu. Tanyakan apapun tentang kulit, dan aku siap bantu dengan saran terbaik!
                    </p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        <a href="{{ route('konsultasi.chat.index') }}" class="btn-gradient gap-2 shadow-xl">
                            💬 Tanya Truevera
                        </a>
                        <a href="{{ route('konsultasi.face-scan.index') }}" class="btn-outline-white gap-2">
                            📸 Face Scan AI
                        </a>
                    </div>
                </div>

                {{-- Stats --}}
                @if($latestScan)
                <div class="shrink-0 glass-card p-6 border border-white/60 text-center md:text-right bg-white/40">
                    <p class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2">Tipe Kulit Kamu</p>
                    <p class="text-3xl font-black text-[var(--tx-primary)] mb-1">{{ $latestScan->skin_type }}</p>
                    <div class="inline-flex items-center gap-2 bg-[var(--tx-secondary)]/10 border border-[var(--tx-secondary)]/20 px-4 py-2 rounded-full mt-2">
                        <span class="text-2xl font-black text-[var(--tx-secondary)]">{{ $latestScan->skin_score }}</span>
                        <span class="text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">/100</span>
                    </div>
                    <p class="text-[10px] text-[var(--tx-text-muted)] font-bold mt-2">{{ $latestScan->score_label }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- 2 FEATURE CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            {{-- Chat AI --}}
            <a href="{{ route('konsultasi.chat.index') }}" class="glass-card p-8 border border-white/50 hover:-translate-y-2 transition-all group block shadow-lg hover:shadow-[var(--tx-secondary)]/20">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-[var(--tx-secondary)] to-[var(--tx-tertiary)] flex items-center justify-center text-3xl shrink-0 shadow-lg group-hover:scale-110 transition-transform border border-white/50">
                        💬
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-[var(--tx-text-dark)] mb-2">Tanya Truevera</h2>
                        <p class="text-sm font-bold text-[var(--tx-text-muted)] leading-relaxed mb-4">Chat langsung dengan AI skincare. Tanya soal rutinitas, bahan aktif, rekomendasi produk, dan masalah kulit spesifikmu.</p>
                        <div class="flex items-center gap-2 text-[var(--tx-secondary)] font-black text-xs uppercase tracking-widest">
                            Mulai Chat <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </div>
                </div>
                @if($recentChats->count())
                <div class="mt-6 pt-5 border-t border-white/50 space-y-2">
                    @foreach($recentChats->take(3) as $chat)
                    <div class="flex items-center justify-between text-xs bg-white/30 px-4 py-2 rounded-[12px] border border-white/40">
                        <span class="font-bold text-[var(--tx-text-dark)] truncate max-w-[200px]">{{ $chat->title }}</span>
                        <span class="text-[var(--tx-text-muted)] font-black shrink-0">{{ $chat->messages_count }} pesan</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </a>

            {{-- Face Scan --}}
            <a href="{{ route('konsultasi.face-scan.index') }}" class="glass-card p-8 border border-white/50 hover:-translate-y-2 transition-all group block shadow-lg hover:shadow-[var(--tx-primary)]/20">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-quaternary)] flex items-center justify-center text-3xl shrink-0 shadow-lg group-hover:scale-110 transition-transform border border-white/50">
                        📸
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-[var(--tx-text-dark)] mb-2">Face Scan AI</h2>
                        <p class="text-sm font-bold text-[var(--tx-text-muted)] leading-relaxed mb-4">Upload foto wajahmu dan AI akan menganalisis tipe kulit, kondisi kulit, serta memberikan rutinitas perawatan yang dipersonalisasi.</p>
                        <div class="flex items-center gap-2 text-[var(--tx-primary)] font-black text-xs uppercase tracking-widest">
                            Scan Sekarang <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </div>
                </div>
                @if($recentScans->count())
                <div class="mt-6 pt-5 border-t border-white/50 space-y-2">
                    @foreach($recentScans->take(3) as $scan)
                    <div class="flex items-center justify-between text-xs bg-white/30 px-4 py-2 rounded-[12px] border border-white/40">
                        <span class="font-black text-[var(--tx-text-dark)]">{{ $scan->skin_type }}</span>
                        <span class="font-black text-[var(--tx-primary)]">Skor {{ $scan->skin_score }}/100</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </a>
        </div>

        {{-- TIPS CEPAT --}}
        <div class="glass-card p-8 border border-white/50">
            <h3 class="font-black text-[var(--tx-text-dark)] text-lg mb-6 flex items-center gap-3">
                <span class="w-10 h-10 rounded-[12px] bg-[var(--tx-quaternary-light)] flex items-center justify-center text-xl border border-white shadow-sm">✨</span>
                Tips Singkat dari Truevera
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach([
                    ['icon' => '🌅', 'title' => 'Rutinitas Pagi', 'tip' => 'Cleanser → Toner → Serum → Moisturizer → Sunscreen SPF50+. Jangan skip sunscreen!', 'color' => 'var(--tx-primary)'],
                    ['icon' => '🌙', 'title' => 'Rutinitas Malam', 'tip' => 'Double cleansing → Toner → Treatment (retinol/AHA) → Night cream yang rich.', 'color' => 'var(--tx-secondary)'],
                    ['icon' => '💧', 'title' => 'Hidrasi Kunci', 'tip' => 'Minum 2L air/hari, gunakan humidifier, dan layer produk hydrating dari tipis ke tebal.', 'color' => 'var(--tx-quaternary)'],
                ] as $tip)
                <div class="bg-white/40 border border-white/60 rounded-[16px] p-5 backdrop-blur-sm hover:-translate-y-1 transition-transform">
                    <div class="text-3xl mb-3">{{ $tip['icon'] }}</div>
                    <h4 class="font-black text-[var(--tx-text-dark)] text-sm mb-2">{{ $tip['title'] }}</h4>
                    <p class="text-[11px] font-bold text-[var(--tx-text-muted)] leading-relaxed">{{ $tip['tip'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
