<x-app-layout title="{{ $product->name }} - Karebla Rewards">
    <div class="py-12 min-h-screen relative z-10" x-data="kareblaDetail({{ $product->coin_price }}, {{ $userPoints }}, {{ $product->stock }}, '{{ route('karebla.redeem') }}', {{ $product->id }})">
        
        <!-- Ambient Orbs -->
        <div class="absolute right-0 top-20 w-96 h-96 bg-gradient-to-bl from-[var(--tx-quaternary-light)] to-[var(--tx-tertiary-light)] rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-80 h-80 bg-gradient-to-tr from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
            
            <a href="{{ route('karebla.index') }}" class="inline-flex items-center gap-2 text-sm font-black text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] mb-6 transition-all glass-card px-4 py-2 rounded-full border border-white/60 bg-white/40 shadow-sm hover:scale-105 hover:bg-white/80 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Katalog
            </a>

            <div class="glass-card rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-white/80 overflow-hidden bg-white/60 backdrop-blur-2xl">
                <div class="flex flex-col lg:flex-row">
                    
                    {{-- Kiri: Image Gallery --}}
                    <div class="lg:w-1/2 p-6 lg:p-10 flex items-center justify-center relative border-b lg:border-b-0 lg:border-r border-white/60">
                        <!-- Glow behind image -->
                        <div class="absolute inset-0 bg-gradient-to-br from-white/40 to-transparent pointer-events-none"></div>
                        
                        @if($product->stock <= 0)
                            <div class="absolute inset-0 bg-white/60 backdrop-blur-md z-10 flex items-center justify-center rounded-[2.5rem] m-6">
                                <span class="bg-gray-800 text-white text-2xl font-black tracking-widest px-8 py-4 rounded-2xl rotate-[-12deg] shadow-2xl border border-gray-600">STOK HABIS</span>
                            </div>
                        @endif

                        @php $images = $product->images ?? []; @endphp
                        @if(!empty($images))
                            <div class="w-full max-w-md relative z-10">
                                <div class="aspect-square rounded-[2rem] overflow-hidden bg-white shadow-lg border border-white/80 mb-6 group">
                                    <img src="{{ $images[0] }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                                </div>
                                @if(count($images) > 1)
                                    <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar px-1">
                                        @foreach($images as $img)
                                            <div class="w-20 h-20 rounded-[1.25rem] overflow-hidden cursor-pointer border-[3px] hover:border-[var(--tx-primary)] transition-all border-white shadow-sm flex-shrink-0 hover:scale-105 opacity-80 hover:opacity-100 bg-white">
                                                <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Kanan: Detail --}}
                    <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col relative">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-[var(--tx-secondary-light)] to-transparent opacity-50 blur-2xl pointer-events-none rounded-bl-full"></div>

                        <div class="mb-8 relative z-10">
                            <div class="flex items-center gap-2 mb-4">
                                @if($product->badge == 'eksklusif')
                                    <span class="bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg border border-white/20 uppercase tracking-widest">✨ Eksklusif</span>
                                @elseif($product->badge == 'terlaris')
                                    <span class="bg-gradient-to-r from-orange-400 to-orange-500 text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg border border-white/20 uppercase tracking-widest">🔥 Terlaris</span>
                                @elseif($product->badge == 'baru')
                                    <span class="bg-gradient-to-r from-[var(--tx-tertiary)] to-purple-500 text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg border border-white/20 uppercase tracking-widest">🆕 Baru</span>
                                @endif
                                
                                @if($product->stock > 0 && $product->stock <= 5)
                                    <span class="bg-red-500 text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg border border-red-400 animate-pulse uppercase tracking-widest">⚠️ Sisa {{ $product->stock }}</span>
                                @endif
                            </div>
                            
                            <p class="text-[10px] font-black text-white bg-[var(--tx-primary)] px-3 py-1 rounded-full w-max uppercase tracking-widest mb-3 shadow-sm">{{ $product->collection }}</p>
                            <h1 class="text-3xl lg:text-5xl font-black text-[var(--tx-text-dark)] mb-4 leading-tight tracking-tight drop-shadow-sm">{{ $product->name }}</h1>
                            
                            <div class="flex items-end gap-3 mb-8 bg-white/40 border border-white/60 px-6 py-4 rounded-[1.5rem] w-max shadow-sm backdrop-blur-md">
                                <span class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] drop-shadow-sm">{{ number_format($product->coin_price, 0, ',', '.') }}</span>
                                <span class="text-sm font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-1.5">Koin Karebla</span>
                            </div>

                            <p class="text-[var(--tx-text-dark)] text-sm font-medium leading-relaxed mb-8 bg-white/40 p-6 rounded-[1.5rem] border border-white/60 shadow-inner">
                                {{ $product->description }}
                            </p>

                            @if(!empty($product->specs))
                                <div class="glass-card bg-white/50 rounded-[1.5rem] p-6 mb-8 border border-white/80 shadow-sm">
                                    <h4 class="font-black text-[var(--tx-text-dark)] text-xs mb-4 uppercase tracking-widest flex items-center gap-2">
                                        <span class="text-lg">📋</span> Spesifikasi
                                    </h4>
                                    <ul class="space-y-3">
                                        @foreach($product->specs as $key => $val)
                                            <li class="flex justify-between items-center text-sm border-b border-white/60 pb-2 last:border-0 last:pb-0">
                                                <span class="text-[var(--tx-text-muted)] font-bold">{{ $key }}</span>
                                                <span class="text-[var(--tx-text-dark)] font-black text-right">{{ $val }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto relative z-10">
                            {{-- Status Koinmu Box --}}
                            @php
                                $percent = min(100, ($userPoints / $product->coin_price) * 100);
                                $shortage = $product->coin_price - $userPoints;
                                $isEnough = $userPoints >= $product->coin_price;
                            @endphp

                            <div class="glass-card {{ $isEnough ? 'bg-gradient-to-br from-[var(--tx-primary-light)]/80 to-[var(--tx-secondary-light)]/80 border-white shadow-[0_10px_30px_rgba(244,114,182,0.15)]' : 'bg-white/40 border-white/60 shadow-sm' }} rounded-[2rem] p-6 mb-6 relative overflow-hidden transition-all">
                                @if($isEnough)
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>
                                @endif

                                <div class="relative z-10">
                                    <div class="flex justify-between items-end mb-3">
                                        <span class="font-black text-xs text-[var(--tx-text-muted)] uppercase tracking-widest">Status Koinmu</span>
                                        <span class="font-black text-xl {{ $isEnough ? 'text-[var(--tx-secondary)]' : 'text-[var(--tx-text-dark)]' }}">{{ number_format($userPoints, 0, ',', '.') }} / {{ number_format($product->coin_price, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="w-full bg-black/5 rounded-full h-3 mb-4 overflow-hidden border border-white/40 shadow-inner">
                                        <div class="h-full rounded-full {{ $isEnough ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)]' : 'bg-gray-400' }} transition-all duration-1000 relative" style="width: {{ $percent }}%">
                                            @if($isEnough)
                                                <div class="absolute top-0 bottom-0 right-0 w-4 bg-white/50 blur-[2px]"></div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($shortage > 0)
                                        <div class="flex justify-between items-center mt-2 bg-white/50 px-4 py-2.5 rounded-xl border border-white/60">
                                            <p class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Kurang <span class="text-red-500">{{ number_format($shortage, 0, ',', '.') }} Koin</span></p>
                                            <a href="{{ route('user.shop.index') }}" class="text-[9px] font-black text-white bg-[var(--tx-primary)] px-3 py-1.5 rounded-lg shadow-sm hover:scale-105 transition-transform uppercase tracking-widest">Belanja &rarr;</a>
                                        </div>
                                    @else
                                        <p class="text-[11px] font-black text-[var(--tx-primary)] flex items-center gap-1.5 mt-2 bg-white/60 px-4 py-2.5 rounded-xl border border-white/80 shadow-sm uppercase tracking-widest">
                                            <span class="text-lg">✨</span> Koin Cukup Untuk Ditukar!
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <button @click="openConfirm" 
                                    class="w-full py-5 rounded-[1.5rem] font-black text-lg flex justify-center items-center gap-3 transition-all transform active:scale-95 uppercase tracking-widest
                                    {{ $product->stock <= 0 || !$isEnough ? 'bg-gray-100/50 text-gray-400 cursor-not-allowed border-2 border-gray-200 backdrop-blur-md' : 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/30 hover:shadow-xl hover:-translate-y-1' }}"
                                    {{ $product->stock <= 0 || !$isEnough ? 'disabled' : '' }}>
                                @if($product->stock <= 0)
                                    Stok Habis 😔
                                @elseif(!$isEnough)
                                    Koin Tidak Cukup 🔒
                                @else
                                    Tukar Sekarang 🎁
                                @endif
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Sheet / Modal Konfirmasi --}}
        <div x-show="showConfirm" class="fixed inset-0 z-50 overflow-hidden" x-cloak>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-md transition-opacity" @click="showConfirm = false"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
            
            <div class="fixed inset-x-0 bottom-0 max-w-lg mx-auto glass-card bg-white/90 rounded-t-[2.5rem] shadow-[0_-20px_60px_rgba(0,0,0,0.1)] transform transition-transform border border-white"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div class="w-16 h-1.5 bg-gray-300/50 rounded-full mx-auto mt-5 mb-3"></div>
                
                <div class="p-8 pt-4">
                    <h3 class="text-2xl font-black text-[var(--tx-text-dark)] text-center mb-6">Konfirmasi Penukaran</h3>

                    <div class="flex items-center gap-4 bg-white/60 p-4 rounded-[1.5rem] mb-6 border border-white shadow-sm backdrop-blur-md">
                        <img src="{{ $images[0] ?? '' }}" alt="" class="w-20 h-20 rounded-xl object-cover bg-white shadow-sm border border-gray-100">
                        <div class="flex-1">
                            <h4 class="font-black text-[var(--tx-text-dark)] text-sm line-clamp-2 mb-2 leading-snug">{{ $product->name }}</h4>
                            <span class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)]">{{ number_format($product->coin_price, 0, ',', '.') }} <span class="text-[10px] text-[var(--tx-text-muted)] font-bold uppercase tracking-widest">Koin</span></span>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8 bg-white/40 p-6 rounded-[1.5rem] border border-white/60 shadow-inner">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-[var(--tx-text-muted)] font-bold uppercase tracking-widest">Saldo Koin Saat Ini</span>
                            <span class="font-black text-[var(--tx-text-dark)] text-sm">{{ number_format($userPoints, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs pb-4 border-b border-gray-200/50">
                            <span class="text-[var(--tx-text-muted)] font-bold uppercase tracking-widest">Koin Digunakan</span>
                            <span class="font-black text-[var(--tx-secondary)] text-sm">-{{ number_format($product->coin_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-black text-[var(--tx-text-dark)] text-xs uppercase tracking-widest">Sisa Setelah Penukaran</span>
                            <span class="font-black text-xl text-[var(--tx-primary)] drop-shadow-sm">{{ number_format($userPoints - $product->coin_price, 0, ',', '.') }}</span>
                        </div>

                        @if(($userPoints - $product->coin_price) == 0)
                            <div class="bg-orange-50/80 border border-orange-200/50 text-orange-800 text-[10px] p-4 rounded-xl flex items-start gap-3 shadow-sm backdrop-blur-md mt-4">
                                <span class="text-xl leading-none">⚠️</span>
                                <p class="font-bold"><strong>Perhatian:</strong> Koin Anda akan habis (0) setelah penukaran ini.</p>
                            </div>
                        @endif

                        <div class="bg-gradient-to-br from-[var(--tx-primary-light)]/50 to-[var(--tx-secondary-light)]/50 border border-white/60 p-5 rounded-2xl shadow-sm mt-4">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] font-black text-[var(--tx-primary)] uppercase tracking-widest">Alamat Pengiriman:</span>
                                <a href="{{ route('profile.edit') }}" class="text-[9px] font-black text-white bg-[var(--tx-primary)] px-3 py-1.5 rounded-lg shadow-sm hover:scale-105 transition-transform uppercase tracking-widest">Ganti</a>
                            </div>
                            <p class="text-xs font-bold text-[var(--tx-text-dark)] leading-relaxed">
                                {{ empty($user->address) ? '⚠️ Alamat belum diisi. Silakan isi alamat di Profil.' : $user->address }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showConfirm = false" class="py-4 rounded-2xl font-black text-[var(--tx-text-muted)] bg-white border-2 border-gray-100 hover:bg-gray-50 transition-all uppercase tracking-widest text-xs">Batal</button>
                        <button @click="processRedeem" :disabled="isProcessing || !hasAddress"
                                class="py-4 rounded-2xl font-black text-white bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] hover:-translate-y-1 transition-all shadow-lg shadow-[var(--tx-primary)]/30 disabled:opacity-50 disabled:hover:translate-y-0 flex justify-center items-center gap-2 uppercase tracking-widest text-xs">
                            <span x-show="!isProcessing">Konfirmasi ✨</span>
                            <span x-show="isProcessing" class="animate-pulse">Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Loading Overlay Full --}}
        <div x-show="isProcessing" class="fixed inset-0 z-[60] bg-white/60 backdrop-blur-xl flex flex-col items-center justify-center" x-cloak>
            <div class="relative w-32 h-32 mb-6 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-[6px] border-gray-100/50"></div>
                <div class="absolute inset-0 rounded-full border-[6px] border-t-[var(--tx-primary)] border-r-[var(--tx-secondary)] border-b-transparent border-l-transparent animate-spin"></div>
                <div class="absolute text-5xl animate-bounce">🎁</div>
            </div>
            <h3 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] mb-2">Membungkus Hadiahmu...</h3>
            <p class="text-sm font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Mohon tunggu sebentar</p>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kareblaDetail', (price, points, stock, url, id) => ({
                showConfirm: false,
                isProcessing: false,
                hasAddress: {{ !empty($user->address) ? 'true' : 'false' }},
                
                openConfirm() {
                    if (stock <= 0 || points < price) return;
                    if (!this.hasAddress) {
                        alert('Silakan lengkapi alamat pengiriman di menu Profil terlebih dahulu.');
                        window.location.href = '{{ route('profile.edit') }}';
                        return;
                    }
                    this.showConfirm = true;
                },

                async processRedeem() {
                    if (this.isProcessing) return;
                    this.isProcessing = true;
                    
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                karebla_product_id: id
                            })
                        });

                        const data = await response.json();
                        
                        // Fake delay for premium feeling
                        await new Promise(r => setTimeout(r, 2000));

                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat menukar.');
                            this.isProcessing = false;
                            this.showConfirm = false;
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan jaringan.');
                        this.isProcessing = false;
                        this.showConfirm = false;
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
