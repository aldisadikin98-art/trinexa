<x-app-layout title="Detail Penukaran - Karebla">
    <div class="py-12 min-h-screen relative z-10">
        
        <!-- Ambient Orbs -->
        <div class="absolute right-0 top-10 w-96 h-96 bg-gradient-to-bl from-[var(--tx-quaternary-light)] to-[var(--tx-tertiary-light)] rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute left-0 bottom-10 w-80 h-80 bg-gradient-to-tr from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 relative z-10">
            
            <a href="{{ route('karebla.history') }}" class="inline-flex items-center gap-2 text-sm font-black text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] mb-6 transition-all glass-card px-4 py-2 rounded-full border border-white/60 bg-white/40 shadow-sm hover:scale-105 hover:bg-white/80 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Riwayat
            </a>

            <div class="glass-card rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-white/80 overflow-hidden bg-white/60 backdrop-blur-2xl">
                
                {{-- Header Status --}}
                @php
                    $statusConfig = [
                        'menunggu' => ['color' => 'from-amber-400 to-amber-500', 'icon' => '🟡', 'text' => 'Menunggu Konfirmasi', 'desc' => 'Admin sedang meninjau permintaan penukaranmu.'],
                        'diproses' => ['color' => 'from-[var(--tx-primary)] to-[var(--tx-secondary)]', 'icon' => '📦', 'text' => 'Sedang Diproses', 'desc' => 'Hore! Hadiahmu sedang disiapkan dan dikemas.'],
                        'dikirim'  => ['color' => 'from-purple-400 to-purple-600', 'icon' => '🚚', 'text' => 'Sedang Dikirim', 'desc' => 'Hadiahmu sedang dalam perjalanan menuju alamatmu!'],
                        'selesai'  => ['color' => 'from-green-400 to-[var(--tx-quaternary)]', 'icon' => '✅', 'text' => 'Selesai', 'desc' => 'Penukaran berhasil! Semoga kamu suka dengan hadiahnya.'],
                    ];
                    $currentStatus = $statusConfig[$redemption->status] ?? $statusConfig['menunggu'];
                @endphp

                <div class="bg-gradient-to-br {{ $currentStatus['color'] }} p-8 md:p-10 text-white relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000 pointer-events-none"></div>
                    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/20 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-[2rem] flex items-center justify-center shadow-inner border border-white/40">
                            <span class="text-4xl drop-shadow-sm">{{ $currentStatus['icon'] }}</span>
                        </div>
                        <div class="text-center md:text-left">
                            <h2 class="text-2xl md:text-3xl font-black mb-2 drop-shadow-sm">{{ $currentStatus['text'] }}</h2>
                            <p class="text-white/90 font-bold text-sm">{{ $currentStatus['desc'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10 space-y-8">
                    {{-- Detail Order --}}
                    <div>
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 pb-6 border-b border-white/60 gap-4">
                            <div class="bg-white/40 px-5 py-3 rounded-[1.5rem] border border-white/60 shadow-sm">
                                <p class="text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mb-1">No. Penukaran</p>
                                <p class="font-black text-[var(--tx-primary)] text-lg">{{ $redemption->receipt_number }}</p>
                            </div>
                            <div class="bg-white/40 px-5 py-3 rounded-[1.5rem] border border-white/60 shadow-sm sm:text-right">
                                <p class="text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mb-1">Tanggal Transaksi</p>
                                <p class="font-black text-[var(--tx-text-dark)]">{{ $redemption->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-5 bg-white/50 p-5 rounded-[2rem] mb-8 border border-white/80 shadow-sm backdrop-blur-md">
                            @php $images = $redemption->product->images ?? []; @endphp
                            <img src="{{ $images[0] ?? '' }}" alt="" class="w-24 h-24 rounded-2xl object-cover bg-white shadow-inner border border-gray-100">
                            <div class="flex-1">
                                <h4 class="font-black text-[var(--tx-text-dark)] text-lg mb-2 leading-tight">{{ $redemption->product->name }}</h4>
                                <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] drop-shadow-sm">-{{ number_format($redemption->coins_used, 0, ',', '.') }} <span class="text-[10px] text-[var(--tx-text-muted)] font-bold uppercase tracking-widest">Koin</span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Pengiriman --}}
                    <div>
                        <h3 class="text-sm font-black text-[var(--tx-text-dark)] uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="text-xl">📍</span> Informasi Pengiriman
                        </h3>
                        <div class="glass-card bg-gradient-to-br from-[var(--tx-primary-light)]/50 to-[var(--tx-secondary-light)]/50 border border-white/60 p-6 rounded-[1.5rem] shadow-sm mb-4">
                            <p class="text-sm text-[var(--tx-text-dark)] font-bold leading-relaxed">{{ $redemption->shipping_address }}</p>
                        </div>
                        
                        @if($redemption->status == 'dikirim' || $redemption->status == 'selesai')
                            <div class="bg-white/40 border border-white/60 p-6 rounded-[1.5rem] shadow-inner mt-4 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-[var(--tx-primary-light)] text-[var(--tx-primary)] flex items-center justify-center text-xl shadow-sm">
                                    🚚
                                </div>
                                <div>
                                    <p class="text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest mb-1">Resi Pengiriman</p>
                                    <p class="font-black text-[var(--tx-text-dark)] text-lg tracking-wider font-mono">{{ $redemption->tracking_number ?? 'Resi belum diinput' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
