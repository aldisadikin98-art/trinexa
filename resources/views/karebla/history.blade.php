<x-app-layout title="Riwayat Penukaran - Karebla">
    <div class="py-12 min-h-screen relative z-10">
        
        <!-- Ambient Orbs -->
        <div class="absolute right-0 top-10 w-96 h-96 bg-gradient-to-bl from-[var(--tx-quaternary-light)] to-[var(--tx-tertiary-light)] rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute left-0 bottom-10 w-80 h-80 bg-gradient-to-tr from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6 relative z-10">
            
            <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
                <h2 class="text-3xl font-black text-[var(--tx-text-dark)] flex items-center gap-3">
                    <span class="text-4xl drop-shadow-sm">📋</span> Riwayat Penukaran
                </h2>
                <a href="{{ route('karebla.index') }}" class="glass-card px-5 py-2.5 rounded-full text-xs font-black text-[var(--tx-text-muted)] hover:text-white hover:bg-gradient-to-r hover:from-[var(--tx-primary)] hover:to-[var(--tx-secondary)] transition-all shadow-sm border border-white/60 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Katalog
                </a>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar px-1">
                @php
                    $tabs = [
                        'semua' => 'Semua',
                        'menunggu' => 'Menunggu 🟡',
                        'diproses' => 'Diproses 🔵',
                        'dikirim' => 'Dikirim 🚚',
                        'selesai' => 'Selesai ✅'
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <a href="{{ route('karebla.history', ['status' => $key]) }}" 
                       class="px-6 py-2.5 rounded-full text-[11px] font-black whitespace-nowrap transition-all shadow-sm border uppercase tracking-widest
                       {{ $filter === $key ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white border-transparent shadow-[var(--tx-primary)]/30' : 'glass-card bg-white/60 text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] hover:bg-white/80 border-white/60' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- List --}}
            <div class="space-y-4">
                @foreach($redemptions as $item)
                    @php
                        $statusColors = [
                            'menunggu' => 'bg-amber-100 text-amber-600 border-amber-200',
                            'diproses' => 'bg-blue-100 text-blue-600 border-blue-200',
                            'dikirim'  => 'bg-purple-100 text-purple-600 border-purple-200',
                            'selesai'  => 'bg-green-100 text-green-600 border-green-200',
                        ];
                        $statusColor = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-500 border-gray-200';
                        $images = $item->product->images ?? [];
                    @endphp

                    <div class="glass-card rounded-[2rem] bg-white/40 border border-white/60 p-5 flex flex-col sm:flex-row gap-5 items-start sm:items-center transition-all hover:shadow-[0_15px_30px_rgba(0,0,0,0.05)] hover:-translate-y-1 hover:bg-white/60 group">
                        <img src="{{ $images[0] ?? '' }}" alt="" class="w-24 h-24 rounded-2xl object-cover bg-white border border-white/80 shadow-inner group-hover:scale-105 transition-transform duration-500">
                        
                        <div class="flex-1 w-full">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-black text-[var(--tx-text-muted)] tracking-widest bg-white/50 px-2 py-1 rounded-md border border-white/60 shadow-sm">{{ $item->receipt_number }}</span>
                                <span class="text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest border shadow-sm {{ $statusColor }}">{{ $item->status }}</span>
                            </div>
                            <h4 class="font-black text-[var(--tx-text-dark)] text-lg mb-1 group-hover:text-[var(--tx-primary)] transition-colors">{{ $item->product->name }}</h4>
                            <p class="text-[10px] text-[var(--tx-text-muted)] font-bold mb-4 uppercase tracking-widest">{{ $item->created_at->format('d M Y, H:i') }}</p>
                            
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-black text-lg text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] drop-shadow-sm">-{{ number_format($item->coins_used, 0, ',', '.') }} Koin</span>
                                <a href="{{ route('karebla.history.detail', $item->id) }}" class="text-[10px] font-black text-white bg-[var(--tx-primary)] px-4 py-2 rounded-xl shadow-sm hover:scale-105 transition-transform uppercase tracking-widest">Detail &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($redemptions->isEmpty())
                <div class="text-center py-20 glass-card bg-white/40 rounded-[3rem] border border-white/60 mt-8">
                    <div class="w-24 h-24 rounded-full bg-white border border-gray-100 shadow-sm flex items-center justify-center text-5xl mx-auto mb-6">📭</div>
                    <h4 class="text-2xl font-black text-[var(--tx-text-dark)] mb-2">Belum ada riwayat penukaran</h4>
                    <p class="text-sm font-bold text-[var(--tx-text-muted)] mb-8 uppercase tracking-widest">Yuk tukar koinmu dengan hadiah eksklusif dari Karebla!</p>
                    <a href="{{ route('karebla.index') }}" class="inline-block px-8 py-3.5 bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white rounded-full font-black shadow-lg shadow-[var(--tx-primary)]/30 hover:-translate-y-1 transition-all uppercase tracking-widest text-xs">Lihat Katalog Hadiah</a>
                </div>
            @endif

            <div class="mt-8">
                {{ $redemptions->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
