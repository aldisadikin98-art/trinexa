<x-app-layout>
    <x-slot name="title">Dashboard | Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ── WELCOME CARD ──────────────────────────────────────── --}}
        <div class="glass-card border border-white/50 p-6 md:p-8 mb-8 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden shadow-xl">
            <div class="absolute inset-0 pointer-events-none opacity-20" style="background: radial-gradient(circle at 80% 50%, var(--tx-secondary) 0%, transparent 55%), radial-gradient(circle at 20% 50%, var(--tx-primary) 0%, transparent 55%);"></div>
            <div class="relative z-10 flex items-center gap-4 md:gap-5 flex-1">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-[20px] overflow-hidden border-4 border-white/60 shadow-lg shrink-0">
                    @if(Auth::user()->avatar)
                        <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center text-xl md:text-2xl font-black">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-xl md:text-3xl font-black text-[var(--tx-text-dark)] mb-1">Halo, {{ Auth::user()->name ?? 'User' }}! ✨</h2>
                    <p class="text-[var(--tx-text-muted)] text-[10px] md:text-sm font-bold">Selamat datang kembali di ekosistem Trinexa.</p>
                </div>
            </div>
            <div class="relative z-10 w-full md:w-auto">
                <div class="flex md:inline-flex items-center justify-center gap-2 bg-white/40 backdrop-blur-sm text-[var(--tx-tertiary)] px-5 py-2.5 rounded-full border border-white/60 font-black text-xs md:text-sm shadow-sm">
                    ⭐ Member Setia
                </div>
            </div>
        </div>

        {{-- ── 3 WIDGET UTAMA ──────────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            {{-- Saldo Harvestly --}}
            <div class="glass-card p-6 md:p-8 relative overflow-hidden border border-white/50 shadow-lg group hover:-translate-y-1 transition-all" style="background: linear-gradient(135deg, var(--tx-primary) 0%, var(--tx-primary-mid) 100%);">
                <div class="absolute -right-6 -top-6 w-28 h-28 bg-white/10 rounded-full group-hover:scale-125 transition-transform duration-700"></div>
                <div class="absolute right-10 -bottom-10 w-20 h-20 bg-white/10 rounded-full group-hover:scale-125 transition-transform duration-700 delay-75"></div>
                <div class="relative z-10 flex items-center gap-2 bg-white/20 w-max px-4 py-2 rounded-xl backdrop-blur-sm text-[10px] md:text-sm font-black mb-6 border border-white/30 text-white uppercase tracking-widest">
                    💳 Saldo Harvestly
                </div>
                <h3 class="relative z-10 text-2xl md:text-4xl font-black tracking-tight mb-8 text-white">Rp {{ number_format($wallet->balance ?? 0, 0, ',', '.') }}</h3>
                <a href="{{ route('user.wallet.topup') }}" class="relative z-10 w-full block bg-white/20 hover:bg-white text-white hover:text-[var(--tx-primary)] font-black py-3 rounded-full text-[11px] md:text-sm backdrop-blur-md transition-all duration-300 border border-white/40 text-center shadow-sm uppercase tracking-widest">
                    ↑ Top Up Sekarang
                </a>
            </div>

            {{-- Target Skincare --}}
            @if($savingGoal)
            <a href="{{ route('user.wallet.show') }}" class="glass-card p-8 flex flex-col justify-center group hover:-translate-y-1 transition-all border border-white/50 shadow-lg">
                <div class="flex justify-between items-end mb-5">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-[var(--tx-secondary-light)] p-2 rounded-xl text-[var(--tx-secondary)]">⚡</span>
                            <h4 class="text-base font-black text-[var(--tx-text-dark)] truncate max-w-[120px]" title="{{ $savingGoal->title }}">{{ $savingGoal->title }}</h4>
                        </div>
                        <p class="text-sm text-[var(--tx-primary)] font-black ml-10">Rp {{ number_format($savingGoal->target_amount, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-base font-black text-[var(--tx-secondary)]">{{ number_format($savingGoal->progress_percent, 1) }}%</span>
                </div>
                <div class="w-full bg-white/40 rounded-full h-3 mb-4 border border-white/60 overflow-hidden">
                    <div class="bg-gradient-to-r from-[var(--tx-secondary)] to-[var(--tx-tertiary)] h-full rounded-full transition-all duration-700" style="width: {{ max(2, $savingGoal->progress_percent) }}%"></div>
                </div>
                <p class="text-xs text-[var(--tx-text-muted)] text-center bg-white/40 py-2.5 rounded-xl font-bold border border-white/50">
                    Terkumpul Rp {{ number_format($savingGoal->current_amount, 0, ',', '.') }}
                </p>
            </a>
            @else
            <a href="{{ route('user.wallet.show') }}" class="glass-card p-8 flex flex-col items-center justify-center text-center group hover:-translate-y-1 transition-all border border-white/50 shadow-lg">
                <div class="w-14 h-14 bg-[var(--tx-secondary-light)] rounded-2xl flex items-center justify-center text-[var(--tx-secondary)] mb-4 group-hover:scale-110 transition-transform text-2xl">🎯</div>
                <h4 class="text-base font-black text-[var(--tx-text-dark)] mb-1">Buat Target Skincare</h4>
                <p class="text-xs text-[var(--tx-text-muted)] font-bold">Nabung otomatis buat produk impianmu!</p>
            </a>
            @endif

            {{-- Truevera AI + Face Scan --}}
            <div class="glass-card p-8 flex flex-col items-center justify-center text-center group hover:-translate-y-1 transition-all border border-white/50 shadow-lg relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 pointer-events-none" style="background: radial-gradient(circle at 50% 30%, var(--tx-secondary) 0%, transparent 70%);"></div>
                <div class="relative z-10 truevera-float mb-4">
                    <svg width="56" height="64" viewBox="0 0 140 160" fill="none">
                        <path d="M46 38 L55 20 L70 32 L85 20 L94 38 Z" fill="#F472B6"/>
                        <circle cx="55" cy="20" r="4" fill="#9B8EC4"/>
                        <circle cx="70" cy="32" r="4" fill="#F472B6"/>
                        <circle cx="85" cy="20" r="4" fill="#9B8EC4"/>
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
                        <rect x="45" y="112" width="50" height="40" rx="18" fill="url(#dashTruevera)"/>
                        <defs><linearGradient id="dashTruevera" x1="45" y1="112" x2="95" y2="152"><stop stop-color="#4A90D9"/><stop offset="1" stop-color="#F472B6"/></linearGradient></defs>
                    </svg>
                </div>
                <h4 class="relative z-10 text-base font-black text-[var(--tx-text-dark)] mb-1">Konsultasi AI Truevera</h4>
                <p class="relative z-10 text-[10px] text-[var(--tx-text-muted)] font-bold mb-5 uppercase tracking-widest">Chat · Face Scan · Rekomendasi</p>
                <div class="relative z-10 flex gap-2 w-full">
                    <a href="{{ route('konsultasi.chat.index') }}" class="flex-1 btn-gradient py-2.5 text-xs gap-1 justify-center">💬 Chat</a>
                    <a href="{{ route('konsultasi.face-scan.index') }}" class="flex-1 bg-white/50 border border-white/70 text-[var(--tx-primary)] font-black text-xs py-2.5 px-3 rounded-full hover:bg-white/70 transition-all text-center">📸 Scan</a>
                </div>
            </div>
        </div>

        {{-- ── QUICK ACCESS ─────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            @foreach([
                ['icon'=>'🛍️','label'=>'Belanja','route'=>'shop.index','color'=>'var(--tx-primary)','bg'=>'var(--tx-primary-light)'],
                ['icon'=>'💎','label'=>'Karebla','route'=>'karebla.index','color'=>'var(--tx-tertiary)','bg'=>'var(--tx-tertiary-light)'],
                ['icon'=>'🩺','label'=>'Dermatology','route'=>'dermatology.index','color'=>'var(--tx-quaternary)','bg'=>'var(--tx-quaternary-light)'],
                ['icon'=>'🏆','label'=>'Loyalty','route'=>'user.loyalty.index','color'=>'var(--tx-secondary)','bg'=>'var(--tx-secondary-light)'],
            ] as $item)
            <a href="{{ route($item['route']) }}" class="glass-card p-5 flex flex-col items-center text-center border border-white/50 hover:-translate-y-1 transition-all group shadow-sm">
                <div class="w-12 h-12 rounded-[14px] flex items-center justify-center text-2xl mb-3 border border-white shadow-sm group-hover:scale-110 transition-transform" style="background: {{ $item['bg'] }}">{{ $item['icon'] }}</div>
                <span class="text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </div>

        {{-- ── EKSPLOR PRODUK ───────────────────────────────────────── --}}
        <div class="flex justify-between items-end mb-6 px-1">
            <h3 class="text-xl font-black text-[var(--tx-text-dark)]">🛍️ Eksplor Produk Naturea</h3>
            <a href="{{ route('shop.index') }}" class="text-xs font-black text-[var(--tx-secondary)] hover:text-[var(--tx-primary)] transition-colors flex items-center gap-1 group uppercase tracking-widest">
                Lihat Semua <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
            @forelse($featuredProducts as $product)
            <a href="{{ route('shop.show', $product->slug) }}" class="glass-card p-3 md:p-5 border border-white/50 hover:-translate-y-2 transition-all group shadow-sm">
                <div class="aspect-square rounded-[12px] md:rounded-[16px] bg-white/40 flex items-center justify-center mb-3 md:mb-4 overflow-hidden border border-white/60">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <span class="text-3xl md:text-5xl">🧴</span>
                    @endif
                </div>
                <span class="text-[8px] md:text-[9px] font-black text-[var(--tx-secondary)] bg-[var(--tx-secondary-light)] border border-white px-2 py-0.5 md:py-1 rounded-full uppercase tracking-wider">Naturea</span>
                <h4 class="text-xs md:text-sm font-black text-[var(--tx-text-dark)] mt-2 md:mt-3 mb-1 truncate">{{ $product->name }}</h4>
                <p class="text-sm md:text-base font-black text-[var(--tx-primary)]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </a>
            @empty
            <div class="col-span-4 glass-card text-center py-16 border border-white/50">
                <p class="text-[var(--tx-text-muted)] font-bold">Belum ada produk tersedia.</p>
            </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
