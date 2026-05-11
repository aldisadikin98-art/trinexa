<x-app-layout>
    <x-slot name="title">Riwayat Pesanan | Trinexa</x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-extrabold text-[#0F2942] mb-6">📦 Riwayat Pesanan</h1>

        {{-- Tabs --}}
        <div class="flex overflow-x-auto hide-scrollbar gap-2 mb-6">
            @foreach($tabs as $key => $label)
                <a href="{{ route('transaction.index', ['status' => $key]) }}"
                   class="shrink-0 px-5 py-2.5 rounded-full text-sm font-bold transition-colors border
                   {{ $status === $key
                       ? 'bg-[#0F2942] text-white border-[#0F2942]'
                       : 'bg-white text-gray-500 border-gray-200 hover:border-[#0F2942] hover:text-[#0F2942]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm">✅ {{ session('success') }}</div>
        @endif

        {{-- Transactions List --}}
        @forelse($transactions as $trx)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 mb-5 hover:shadow-md transition-shadow">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🛍️</span>
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Belanja • {{ $trx->created_at->format('d M Y') }}</div>
                            <div class="font-black text-[#0F2942]">{{ $trx->receipt_number }}</div>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-black {{ $trx->status_color }}">
                        {{ $trx->status_label }}
                    </span>
                </div>

                {{-- Preview Items --}}
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1 space-y-3">
                        @foreach($trx->items->take(2) as $item)
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->product->primary_image }}" class="w-12 h-12 rounded-xl object-cover" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&q=80'">
                                <div>
                                    <p class="font-bold text-sm text-gray-800 line-clamp-1">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} produk x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                        @if($trx->items->count() > 2)
                            <p class="text-xs text-gray-400 font-medium">+ {{ $trx->items->count() - 2 }} produk lainnya</p>
                        @endif
                    </div>

                    {{-- Total --}}
                    <div class="md:w-48 shrink-0 md:border-l md:border-gray-100 md:pl-6 flex flex-col justify-center">
                        <p class="text-xs text-gray-500 font-bold mb-1">Total Belanja</p>
                        <p class="font-black text-lg text-[#0F2942] mb-1">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</p>
                        @if($trx->coins_earned > 0)
                            <p class="text-[10px] font-bold text-[#2DD4A0]">🪙 +{{ $trx->coins_earned }} Koin</p>
                        @endif
                    </div>
                </div>

                {{-- Action --}}
                <div class="mt-5 pt-4 border-t border-gray-100 flex justify-end">
                    <a href="{{ route('transaction.show', $trx) }}" class="text-sm font-bold text-[#D4AF37] hover:text-[#b8952d] hover:underline px-4 py-2">
                        Lihat Detail Transaksi
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-lg font-bold text-gray-700">Belum ada pesanan</h3>
                <p class="text-gray-400 text-sm mt-1">Kamu belum memiliki transaksi dengan status ini.</p>
                <a href="{{ route('shop.index') }}" class="mt-6 inline-block bg-[#D4AF37] text-white font-bold px-6 py-2.5 rounded-xl hover:bg-[#b8952d] transition-colors">
                    Mulai Belanja
                </a>
            </div>
        @endforelse

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
