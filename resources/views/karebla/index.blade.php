<x-app-layout>
    <x-slot name="title">Karebla | Trinexa Exclusive Rewards</x-slot>

    <!-- KONTEN UTAMA -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-28 md:pb-12 relative z-10">
        
        <!-- Header & Banner -->
        <div class="mb-10 text-center md:text-left relative">
            <h2 class="text-3xl md:text-5xl font-black text-[var(--tx-text-dark)] tracking-tight mb-3 flex flex-col md:flex-row items-center md:justify-start justify-center gap-4">
                <img src="{{ asset('images/logo karebla.jpeg') }}" alt="Karebla" class="w-12 h-12 md:w-16 md:h-16 rounded-2xl object-cover shadow-md border-2 border-white">
                <span>Karebla Rewards</span>
            </h2>
            <p class="text-[var(--tx-text-muted)] font-bold text-sm md:text-lg max-w-2xl px-4 md:px-0">
                Tukar koin belanjamu dengan merchandise eksklusif dan produk unggulan.
            </p>
            
            <div class="absolute right-0 top-0 w-48 h-48 md:w-64 md:h-64 bg-gradient-to-bl from-[var(--tx-quaternary-light)] to-[var(--tx-tertiary-light)] rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl opacity-60 pointer-events-none overflow-hidden"></div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 mb-10">
            <!-- Kiri: Stats -->
            <div class="flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 h-full">
                    
                    <!-- Total XP -->
                    <div class="glass-card rounded-[2rem] md:rounded-[2.5rem] border border-white/60 p-6 md:p-8 flex flex-col justify-center items-center text-center shadow-lg hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden group">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-amber-200 rounded-full blur-2xl opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.5rem] bg-gradient-to-br from-amber-300 to-amber-500 text-white flex items-center justify-center text-2xl md:text-3xl mb-4 shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform z-10 relative">🪙</div>
                        <span class="text-[9px] md:text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mb-1 z-10 relative">Koin Tersedia</span>
                        <span class="text-3xl md:text-4xl font-black text-[var(--tx-text-dark)] z-10 relative">{{ number_format($userPoints, 0, ',', '.') }}</span>
                    </div>
 
                    <!-- Ditukar -->
                    <div class="glass-card rounded-[2rem] md:rounded-[2.5rem] border border-white/60 p-6 md:p-8 flex flex-col justify-center items-center text-center shadow-lg hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden group">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-[var(--tx-secondary-light)] rounded-full blur-2xl opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.5rem] bg-gradient-to-br from-[var(--tx-secondary)] to-[var(--tx-pink)] text-white flex items-center justify-center text-2xl md:text-3xl mb-4 shadow-lg shadow-[var(--tx-secondary)]/30 group-hover:scale-110 transition-transform z-10 relative">🎁</div>
                        <span class="text-[9px] md:text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mb-1 z-10 relative">Telah Ditukar</span>
                        <span class="text-3xl md:text-4xl font-black text-[var(--tx-text-dark)] z-10 relative">{{ $totalRedeemed }}</span>
                    </div>


                    <!-- Diproses -->
                    <div class="glass-card rounded-[2.5rem] border border-white/60 p-8 flex flex-col justify-center items-center text-center shadow-lg hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden group">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-[var(--tx-primary-light)] rounded-full blur-2xl opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center text-3xl mb-4 shadow-lg shadow-[var(--tx-primary)]/30 group-hover:scale-110 transition-transform z-10 relative">📦</div>
                        <span class="text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mb-1 z-10 relative">Sedang Diproses</span>
                        <span class="text-4xl font-black text-[var(--tx-text-dark)] z-10 relative">{{ $activeRedeemed }}</span>
                        @if($activeRedeemed > 0)
                            <span class="absolute top-6 right-6 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[var(--tx-primary)] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-[var(--tx-primary)] border-2 border-white"></span>
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Kanan: Membership Card -->
            <div class="w-full lg:w-96 shrink-0 relative group cursor-pointer" style="perspective: 1000px;">
                @php
                    $cardClass = 'bg-white/60 text-[var(--tx-text-dark)] border-white/80';
                    $icon = '🌱';
                    $bonusText = 'Belanja untuk naik level';
                    $barColor = 'bg-[var(--tx-quaternary)]';
                    
                    if ($userLevel === 'Loyal') {
                        $cardClass = 'bg-gradient-to-br from-[var(--tx-primary-light)] to-white border-[var(--tx-primary)]/20';
                        $icon = '⭐';
                        $bonusText = 'Akses Badge Eksklusif';
                        $barColor = 'bg-[var(--tx-primary)]';
                    } elseif ($userLevel === 'Premium') {
                        $cardClass = 'bg-gradient-to-br from-[var(--tx-secondary-light)] to-white border-[var(--tx-secondary)]/20';
                        $icon = '💎';
                        $bonusText = 'Bonus Koin +10%';
                        $barColor = 'bg-[var(--tx-secondary)]';
                    } elseif ($userLevel === 'VIP') {
                        $cardClass = 'bg-gradient-to-br from-[#1E293B] to-[#0F2942] text-white border-white/10 shadow-[0_15px_40px_rgba(15,41,66,0.4)]';
                        $icon = '👑';
                        $bonusText = 'Bonus Koin +20%';
                        $barColor = 'bg-gradient-to-r from-amber-300 to-amber-500';
                    }
                @endphp

                <div class="glass-card rounded-[2.5rem] p-8 transition-transform duration-500 transform group-hover:-translate-y-2 h-full flex flex-col justify-between shadow-xl {{ $cardClass }} border backdrop-blur-xl relative overflow-hidden">
                    @if($userLevel === 'VIP')
                        <div class="absolute -right-20 -top-20 w-60 h-60 bg-amber-500 rounded-full blur-[80px] opacity-20 pointer-events-none"></div>
                    @else
                        <div class="absolute -right-20 -top-20 w-60 h-60 bg-white rounded-full blur-[60px] opacity-50 pointer-events-none"></div>
                    @endif

                    <div class="relative z-10 flex justify-between items-start mb-6">
                        <div>
                            <h4 class="text-[10px] font-black opacity-60 mb-2 uppercase tracking-widest">Status Membership</h4>
                            <div class="flex items-center gap-3">
                                <span class="text-4xl drop-shadow-sm bg-white/20 p-2 rounded-2xl border border-white/20 backdrop-blur-md">{{ $icon }}</span>
                                <span class="font-black text-3xl tracking-tight bg-clip-text text-transparent {{ $userLevel === 'VIP' ? 'bg-gradient-to-r from-amber-200 to-amber-500' : 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)]' }}">{{ $userLevel }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-base md:text-lg truncate mb-0.5">{{ $user->name }}</p>
                            <p class="text-[10px] md:text-xs font-bold opacity-60">Member sejak {{ $joinDate }}</p>
                        </div>
                    </div>

                    <div class="bg-white/20 rounded-[1.5rem] p-4 md:p-5 backdrop-blur-md border border-white/30 shadow-inner relative z-10">
                        <div class="flex justify-between items-center mb-3 text-[10px] md:text-xs font-black">
                            <span>{{ $transactionCount }} Transaksi</span>
                            <span>{{ $progress == 100 ? 'Level Maksimal' : $progress . '%' }}</span>
                        </div>
                        <div class="w-full bg-black/10 rounded-full h-2.5 mb-4 border border-white/20 overflow-hidden shadow-inner">
                            <div class="{{ $barColor }} h-full rounded-full transition-all duration-1000 relative" style="width: {{ $progress }}%">
                                @if($userLevel === 'VIP')
                                <div class="absolute top-0 right-0 bottom-0 w-4 bg-white/50 blur-[2px]"></div>
                                @endif
                            </div>
                        </div>
                        <p class="text-[9px] md:text-[10px] font-black text-center {{ $userLevel === 'VIP' ? 'bg-amber-500/20 text-amber-300' : 'bg-white/50 text-[var(--tx-text-dark)]' }} px-3 py-1.5 rounded-xl uppercase tracking-widest">{{ $bonusText }}</p>
                    </div>
                </div>{{-- end glass-card --}}
            </div>{{-- end right column --}}
        </div>{{-- end flex row --}}

        <!-- Banner Info -->
        <div class="glass-card rounded-[2.5rem] p-8 md:p-10 mb-12 flex flex-col md:flex-row items-center justify-between gap-8 shadow-xl relative overflow-hidden bg-gradient-to-br from-[var(--tx-quaternary)] to-[#7BB3E8] text-white border border-white/40 group hover:-translate-y-1 transition-transform duration-300">
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/20 rounded-full blur-[80px] pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10 text-center md:text-left">
                <div class="bg-white/20 p-5 rounded-[1.5rem] text-4xl shadow-lg border border-white/30 transform group-hover:rotate-12 transition-transform duration-500 backdrop-blur-md shrink-0">
                    💡
                </div>
                <div>
                    <h4 class="font-black text-2xl mb-2">Cara Mendapatkan Koin Karebla?</h4>
                    <p class="text-sm font-bold text-white/90 leading-relaxed max-w-2xl">
                        Selesaikan misi harian di Dashboard atau belanja produk. Setiap transaksi sukses akan otomatis memberikan koin loyalty. Kumpulkan terus koinnya dan tukarkan dengan hadiah atau merchandise super cute!
                    </p>
                </div>
            </div>
            <a href="{{ route('karebla.history') }}" class="px-8 py-3.5 bg-white/90 backdrop-blur-md text-[var(--tx-quaternary)] hover:bg-white rounded-full font-black text-sm transition-all shadow-lg hover:shadow-xl shrink-0 whitespace-nowrap hover:scale-105 active:scale-95 relative z-10 uppercase tracking-widest">
                📋 Lihat Riwayat
            </a>
        </div>

        <!-- Katalog & Filter -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-6">
                <h3 class="text-3xl font-black text-[var(--tx-text-dark)] flex items-center gap-3">
                    <span class="text-4xl drop-shadow-sm">🎁</span> Katalog Hadiah
                </h3>
                
                <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar px-1">
                    @php
                        $filters = [
                            'semua' => 'Semua',
                            'bisa_ditukar' => 'Bisa Ditukar ✓',
                            'terbaru' => 'Terbaru ✨',
                            'terendah' => 'XP Terendah ⬇️',
                            'tertinggi' => 'XP Tertinggi ⬆️'
                        ];
                    @endphp
                    @foreach($filters as $key => $label)
                        <a href="{{ route('karebla.index', ['filter' => $key]) }}" 
                            class="px-6 py-2.5 rounded-full text-xs font-black whitespace-nowrap transition-all shadow-sm border {{ $filter === $key ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white border-transparent shadow-[var(--tx-primary)]/30' : 'glass-card bg-white/60 text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] hover:bg-white/80' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                    @php
                        $isEnough = $userPoints >= $product->coin_price;
                        $isAlmost = $userPoints >= ($product->coin_price * 0.8) && !$isEnough;
                        $isOut = $product->stock <= 0;
                        $isLow = $product->stock > 0 && $product->stock <= 5;
                        
                        $cardClass = $isEnough && !$isOut ? 'border-white/80 shadow-[0_15px_30px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_40px_rgba(244,114,182,0.15)]' : 'border-white/40 opacity-90';
                        $imgClass = $isOut ? 'grayscale opacity-50' : '';
                    @endphp

                    <div class="glass-card rounded-[2.5rem] bg-white/40 flex flex-col relative transition-all duration-300 hover:-translate-y-2 {{ $cardClass }} group overflow-hidden">
                        
                        {{-- Badges --}}
                        <div class="absolute top-5 left-5 z-20 flex flex-col gap-2">
                            @if($isOut)
                                <span class="bg-gray-800/90 backdrop-blur-md text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg transform -rotate-3 border border-gray-600">HABIS</span>
                            @elseif($isLow)
                                <span class="bg-red-500/90 backdrop-blur-md text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg animate-pulse transform -rotate-3 border border-red-400">SISA {{ $product->stock }}</span>
                            @endif

                            @if($product->badge == 'eksklusif')
                                <span class="bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg border border-white/20">✨ EKSKLUSIF</span>
                            @elseif($product->badge == 'terlaris')
                                <span class="bg-gradient-to-r from-orange-400 to-orange-500 text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg border border-white/20">🔥 TERLARIS</span>
                            @elseif($product->badge == 'baru')
                                <span class="bg-gradient-to-r from-[var(--tx-tertiary)] to-purple-500 text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg border border-white/20">🆕 BARU</span>
                            @endif
                        </div>

                        {{-- Product Image --}}
                        <a href="{{ route('karebla.show', $product->slug) }}" class="block relative aspect-square overflow-hidden bg-gradient-to-b from-white/60 to-transparent">
                            <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out {{ $imgClass }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>

                        {{-- Product Info --}}
                        <div class="p-6 flex flex-col flex-1 bg-white/50 backdrop-blur-md border-t border-white/60">
                            <span class="text-[9px] font-black text-white bg-[var(--tx-primary)]/80 backdrop-blur-md px-2.5 py-1 rounded-full w-max mb-3 uppercase tracking-widest shadow-sm">{{ $product->collection }}</span>
                            <h4 class="font-black text-[var(--tx-text-dark)] text-lg mb-4 line-clamp-2 leading-tight group-hover:text-[var(--tx-primary)] transition-colors">{{ $product->name }}</h4>
                            
                            <div class="mt-auto">
                                <div class="flex items-end justify-between mb-4">
                                    <div class="font-black text-2xl text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] drop-shadow-sm">
                                        {{ number_format($product->coin_price, 0, ',', '.') }} <span class="text-[10px] text-[var(--tx-text-muted)] font-bold uppercase">Koin</span>
                                    </div>
                                </div>

                                {{-- Progress Bar --}}
                                @php
                                    $percent = min(100, ($userPoints / $product->coin_price) * 100);
                                @endphp
                                <div class="w-full bg-black/5 border border-white/40 rounded-full h-2.5 mb-5 overflow-hidden relative shadow-inner">
                                    <div class="h-full rounded-full {{ $isEnough ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)]' : ($isAlmost ? 'bg-gradient-to-r from-orange-300 to-orange-500' : 'bg-gray-400') }} transition-all duration-1000 relative" style="width: {{ $percent }}%">
                                        @if($isEnough)
                                            <div class="absolute right-0 top-0 bottom-0 w-2 bg-white/60 blur-[1px]"></div>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ route('karebla.show', $product->slug) }}" class="block w-full text-center py-3.5 rounded-full text-[11px] font-black uppercase tracking-widest transition-all {{ $isOut ? 'bg-gray-100/50 text-gray-400 cursor-not-allowed border border-gray-200 shadow-inner' : ($isEnough ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95' : 'bg-white border border-[var(--tx-primary)]/30 text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] hover:border-[var(--tx-primary)] hover:bg-[var(--tx-primary-light)]') }}">
                                    {{ $isOut ? 'Stok Habis' : ($isEnough ? 'Tukar Sekarang ✨' : 'Lihat Detail') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($products->isEmpty())
                <div class="text-center py-20 glass-card bg-white/40 rounded-[3rem] border border-white/60">
                    <div class="w-24 h-24 rounded-full bg-white border border-gray-100 shadow-sm flex items-center justify-center text-5xl mx-auto mb-6">😔</div>
                    <h4 class="text-3xl font-black text-[var(--tx-text-dark)] mb-3">Katalog Kosong</h4>
                    <p class="text-sm font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Coba ubah filter atau kembali lagi nanti ya.</p>
                </div>
            @endif
        </div>
    </main>
</x-app-layout>
