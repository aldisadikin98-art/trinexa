<x-app-layout>
    <x-slot name="title">Dompet Harvestly | Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-white/60 backdrop-blur-md border border-white/50 text-[var(--tx-quaternary)] font-black text-sm px-6 py-4 rounded-[16px] shadow-lg flex items-center gap-3">
                <span class="text-xl">🌟</span>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-white/60 backdrop-blur-md border border-white/50 text-red-500 font-black text-sm px-6 py-4 rounded-[16px] shadow-lg flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                {{ session('error') }}
            </div>
        @endif

        {{-- Balance Card + Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            {{-- Balance Card --}}
            <div class="md:col-span-2 relative overflow-hidden rounded-[24px] shadow-xl shadow-[var(--tx-primary)]/20 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-tertiary)] border border-white/40">
                {{-- Decorative circles --}}
                <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full bg-white/20 blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 -left-10 w-48 h-48 rounded-full bg-[var(--tx-secondary)]/30 blur-2xl pointer-events-none"></div>

                <div class="relative p-8 md:p-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-[12px] bg-white flex items-center justify-center overflow-hidden shadow-inner border border-white/30">
                                <img src="{{ asset('images/logo harves.jpeg') }}" alt="Harves" class="w-full h-full object-cover">
                            </div>
                            <span class="text-white/90 font-black text-sm tracking-widest uppercase drop-shadow-sm">Harvestly Wallet</span>
                        </div>
                        <span class="bg-white/20 backdrop-blur-sm border border-white/30 px-3 py-1 rounded-full text-white/90 text-[10px] font-black uppercase tracking-widest">{{ auth()->user()->name }}</span>
                    </div>

                    <div class="mb-8 flex-grow" x-data="{ show: true }">
                        <p class="text-white/80 text-[10px] font-black uppercase tracking-widest mb-2 drop-shadow-sm">Total Saldo</p>
                        <div class="flex items-end gap-3">
                            <h2 class="font-black tracking-tight text-white leading-none drop-shadow-md"
                                :class="show ? 'text-4xl md:text-5xl' : 'text-4xl'"
                                x-text="show ? 'Rp {{ number_format($wallet->balance, 0, ',', '.') }}' : '••••••••'">
                            </h2>
                            <button @click="show = !show" class="text-white/60 hover:text-white transition-colors mb-2 bg-white/10 p-1.5 rounded-lg backdrop-blur-sm">
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <p class="text-white/60 text-[10px] font-bold mt-3 bg-black/10 inline-block px-2 py-0.5 rounded border border-white/10">Diperbarui: {{ now()->translatedFormat('d M, H:i') }}</p>
                    </div>

                    {{-- Quick Action Buttons --}}
                    <div class="grid grid-cols-2 gap-4 mt-auto">
                        <a href="{{ route('user.wallet.topup') }}"
                           class="flex items-center justify-center gap-2 font-black py-3.5 px-4 rounded-[16px] text-xs transition-all bg-white text-[var(--tx-primary)] hover:bg-white/90 shadow-lg hover:shadow-white/20 hover:-translate-y-1 border border-white/50 uppercase tracking-widest">
                            <span>⚡</span> Top Up
                        </a>
                        <a href="{{ route('user.wallet.withdraw') }}"
                           class="flex items-center justify-center gap-2 font-black py-3.5 px-4 rounded-[16px] text-xs border-2 border-white/60 text-white hover:bg-white/20 transition-all backdrop-blur-sm hover:-translate-y-1 shadow-sm uppercase tracking-widest">
                            <span>💸</span> Tarik Saldo
                        </a>
                    </div>
                </div>
            </div>

            {{-- Statistik Bulan Ini --}}
            <div class="flex flex-col gap-4">
                <div class="glass-card p-5 border border-white/50 flex-1 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-[12px] bg-[var(--tx-quaternary-light)] flex items-center justify-center border border-white">
                            <span class="text-xl">📥</span>
                        </div>
                        <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Pemasukan</span>
                    </div>
                    <div class="font-black text-2xl text-[var(--tx-quaternary)] drop-shadow-sm group-hover:scale-105 origin-left transition-transform">Rp {{ number_format($totalIn, 0, ',', '.') }}</div>
                </div>

                <div class="glass-card p-5 border border-white/50 flex-1 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-[12px] bg-pink-100 flex items-center justify-center border border-white">
                            <span class="text-xl">📤</span>
                        </div>
                        <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Pengeluaran</span>
                    </div>
                    <div class="font-black text-2xl text-[var(--tx-secondary)] drop-shadow-sm group-hover:scale-105 origin-left transition-transform">Rp {{ number_format($totalOut, 0, ',', '.') }}</div>
                </div>

                <div class="glass-card p-5 border border-white/50 flex-1 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-[12px] bg-[var(--tx-tertiary-light)] flex items-center justify-center border border-white">
                            <span class="text-xl">🔄</span>
                        </div>
                        <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Total Transaksi</span>
                    </div>
                    <div class="font-black text-2xl text-[var(--tx-tertiary)] drop-shadow-sm flex items-end gap-1 group-hover:scale-105 origin-left transition-transform">{{ $txCount }} <span class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-70">Aktivitas</span></div>
                </div>
            </div>
        </div>

        {{-- Quick Actions Row --}}
        <div class="grid grid-cols-4 gap-4 mb-8">
            @php
                $actions = [
                    ['label' => 'Top Up', 'icon' => '⚡', 'route' => route('user.wallet.topup'), 'bg' => 'bg-[var(--tx-primary)] text-white border-white/40'],
                    ['label' => 'Tarik', 'icon' => '💸', 'route' => route('user.wallet.withdraw'), 'bg' => 'bg-[var(--tx-secondary)] text-white border-white/40'],
                    ['label' => 'Riwayat', 'icon' => '📋', 'route' => route('user.wallet.history'), 'bg' => 'bg-[var(--tx-tertiary)] text-white border-white/40'],
                    ['label' => 'Belanja', 'icon' => '🛍️', 'route' => route('shop.index'), 'bg' => 'bg-[var(--tx-quaternary)] text-white border-white/40'],
                ];
            @endphp
            @foreach($actions as $action)
                <a href="{{ $action['route'] }}" class="glass-card flex flex-col items-center justify-center gap-3 py-6 px-2 hover:-translate-y-1 hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 rounded-[14px] {{ $action['bg'] }} flex items-center justify-center text-xl shadow-inner border group-hover:scale-110 transition-transform">
                        {{ $action['icon'] }}
                    </div>
                    <span class="font-black text-[var(--tx-text-dark)] text-[10px] uppercase tracking-widest">{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Transaksi Terakhir --}}
        <div class="glass-card flex flex-col border border-white/50">
            <div class="flex justify-between items-center px-8 py-6 border-b border-white/50 bg-white/30">
                <h3 class="font-black text-[var(--tx-text-dark)] text-lg">📝 Transaksi Terakhir</h3>
                <a href="{{ route('user.wallet.history') }}" class="text-[10px] font-black text-[var(--tx-primary)] hover:text-white hover:bg-[var(--tx-primary)] transition-colors bg-white/60 border border-white px-4 py-1.5 rounded-full uppercase tracking-wider">
                    Lihat Semua
                </a>
            </div>

            <div class="p-4 divide-y divide-white/40">
                @forelse($recentTransactions as $tx)
                    <div class="flex items-center gap-4 px-4 py-4 rounded-[16px] hover:bg-white/40 transition-colors group">
                        @php
                            $isIncome = $tx->is_income;
                            $iconData = match($tx->type) {
                                'topup'      => ['icon' => '💳', 'bg' => 'bg-[var(--tx-primary-light)] text-[var(--tx-primary)]'],
                                'purchase'   => ['icon' => '🛍️', 'bg' => 'bg-[var(--tx-secondary-light)] text-[var(--tx-secondary)]'],
                                'withdrawal' => ['icon' => '💸', 'bg' => 'bg-[var(--tx-tertiary-light)] text-[var(--tx-tertiary)]'],
                                'reward'     => ['icon' => '🎁', 'bg' => 'bg-[var(--tx-quaternary-light)] text-[var(--tx-quaternary)]'],
                                'credit'     => ['icon' => '↩️', 'bg' => 'bg-[var(--tx-primary-light)] text-[var(--tx-primary)]'],
                                default      => ['icon' => '💰', 'bg' => 'bg-gray-100 text-gray-500'],
                            };
                        @endphp
                        <div class="w-12 h-12 rounded-[14px] flex items-center justify-center shrink-0 text-xl border border-white shadow-sm {{ $iconData['bg'] }} group-hover:scale-110 transition-transform">
                            {{ $iconData['icon'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-[var(--tx-text-dark)] text-sm truncate">{{ $tx->type_label }}</p>
                            <p class="text-[10px] font-bold text-[var(--tx-text-muted)] mt-1 uppercase tracking-widest">{{ $tx->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-black text-base {{ $isIncome ? 'text-[var(--tx-quaternary)]' : 'text-[var(--tx-secondary)]' }}">
                                {{ $isIncome ? '+' : '−' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </p>
                            <span class="inline-block mt-1 text-[9px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full border {{ $tx->status_color }} bg-white/50">
                                {{ $tx->status_label }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="text-6xl mb-6 opacity-50 grayscale">💳</div>
                        <p class="font-black text-[var(--tx-text-dark)] text-lg mb-2">Belum ada transaksi</p>
                        <p class="text-xs text-[var(--tx-text-muted)] font-bold mb-6">Mulai dengan Top Up saldo Harvestly-mu!</p>
                        <a href="{{ route('user.wallet.topup') }}" class="btn-gradient">
                            ⚡ Top Up Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
