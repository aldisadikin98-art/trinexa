<x-app-layout>
    <x-slot name="title">Riwayat Pesanan | Trinexa</x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center text-2xl shadow-lg border border-white/50">📦</div>
            <div>
                <h1 class="text-3xl font-black text-[var(--tx-text-dark)] leading-tight">Riwayat Pesanan</h1>
                <p class="text-[var(--tx-text-muted)] font-bold text-sm italic">Cek status dan detail belanjamu ✨</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex overflow-x-auto hide-scrollbar gap-3 mb-8 p-1.5 bg-white/30 backdrop-blur-md rounded-full border border-white/50 shadow-sm">
            @foreach($tabs as $key => $label)
                <a href="{{ route('transaction.index', ['status' => $key]) }}"
                   class="shrink-0 px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all
                   {{ $status === $key
                       ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-md shadow-[var(--tx-primary)]/20'
                       : 'text-[var(--tx-text-muted)] hover:bg-white/50 hover:text-[var(--tx-primary)]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if(session('success'))
            <div class="mb-8 flex items-center gap-4 bg-white/60 backdrop-blur-md border border-white/80 text-[var(--tx-quaternary)] px-6 py-4 rounded-[2rem] text-sm font-black shadow-lg shadow-[var(--tx-quaternary)]/5 animate-bounce-subtle">
                <span class="text-xl">🌟</span> {{ session('success') }}
            </div>
        @endif

        {{-- Transactions List --}}
        <div class="space-y-6">
            @forelse($transactions as $trx)
                <div class="bg-white/50 backdrop-blur-md rounded-[2.5rem] border border-white/70 overflow-hidden hover:shadow-2xl hover:shadow-[var(--tx-primary)]/5 transition-all duration-500 group">
                    {{-- Header Card --}}
                    <div class="bg-white/40 px-6 py-5 flex flex-wrap items-center justify-between gap-4 border-b border-white/40">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-white to-[var(--tx-cream)] flex items-center justify-center text-2xl shadow-sm border border-white relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] opacity-10"></div>
                                🛍️
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-0.5">{{ $trx->created_at->format('d M Y • H:i') }}</p>
                                <p class="font-black text-[var(--tx-text-dark)] text-sm tracking-wide">{{ $trx->receipt_number }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm border border-white {{ $trx->status_color }}">
                                {{ $trx->status_label }}
                            </span>
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="p-6 flex flex-col lg:flex-row gap-8 items-center">
                        <div class="flex-1 w-full space-y-4">
                            @foreach($trx->items->take(2) as $item)
                                <div class="flex items-center gap-5 group/item bg-white/30 p-3 rounded-2xl border border-white/40 hover:bg-white/50 transition-colors">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-white shadow-sm shrink-0 group-hover/item:scale-105 transition-transform duration-500">
                                        <img src="{{ $item->product->primary_image }}" class="w-full h-full object-cover mix-blend-multiply">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-black text-[var(--tx-text-dark)] text-sm mb-1 truncate">{{ $item->product->name }}</h4>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-bold text-[var(--tx-text-muted)] bg-white/60 px-2 py-0.5 rounded-md border border-white/50">
                                                {{ $item->quantity }} Item
                                            </span>
                                            <span class="text-xs font-black text-[var(--tx-primary)]">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if($trx->items->count() > 2)
                                <div class="flex justify-center">
                                    <p class="text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest bg-white/40 px-4 py-1.5 rounded-full border border-white/50 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--tx-primary)] animate-pulse"></span>
                                        + {{ $trx->items->count() - 2 }} Produk Lainnya
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Price Section --}}
                        <div class="w-full lg:w-56 shrink-0 bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] rounded-3xl p-6 text-white shadow-lg relative overflow-hidden group/price">
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover/price:scale-150 transition-transform duration-700"></div>
                            <div class="relative z-10">
                                <p class="text-[9px] font-black text-white/70 uppercase tracking-widest mb-1.5">Total Bayar</p>
                                <p class="text-xl font-black mb-2 tracking-tight">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</p>
                                @if($trx->coins_earned > 0)
                                    <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-xl text-[9px] font-black border border-white/20">
                                        🪙 +{{ number_format($trx->coins_earned, 0, ',', '.') }} Koin
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="w-full lg:w-auto">
                            <a href="{{ route('transaction.show', $trx) }}" class="block text-center btn-gradient px-8 py-4 text-xs font-black uppercase tracking-[0.15em] rounded-2xl shadow-xl hover:-translate-y-1 transition-all">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-card text-center py-24 border border-white/50 rounded-[3rem]">
                    <div class="w-32 h-32 bg-white/50 rounded-full flex items-center justify-center text-7xl mx-auto mb-8 shadow-inner border border-white animate-float">📦</div>
                    <h3 class="text-2xl font-black text-[var(--tx-text-dark)] mb-2">Ups! Belum Ada Pesanan</h3>
                    <p class="text-[var(--tx-text-muted)] font-bold mb-8 max-w-sm mx-auto">Sepertinya kamu belum pernah belanja atau belum ada pesanan dengan status ini.</p>
                    <a href="{{ route('shop.index') }}" class="btn-gradient inline-flex items-center gap-3 px-10 py-4 font-black uppercase tracking-widest shadow-xl">
                        🛒 Mulai Belanja Sekarang
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="mt-12 bg-white/40 backdrop-blur-md p-4 rounded-3xl border border-white/60">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
