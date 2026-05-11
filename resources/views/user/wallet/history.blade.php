<x-app-layout>
    <x-slot name="title">Riwayat Transaksi | Harvestly Trinexa</x-slot>

    <div class="max-w-2xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('user.wallet.show') }}" class="inline-flex items-center gap-1 text-[#D4AF37] font-bold text-sm mb-2 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-extrabold text-[#0F2942]">Riwayat Transaksi</h1>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400">Saldo Saat Ini</p>
                <p class="font-black text-[#0F2942]">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Filter Tabs --}}
        @php
            $filters = [
                'semua'   => 'Semua',
                'masuk'   => '⬇ Masuk',
                'keluar'  => '⬆ Keluar',
                'belanja' => '🛍 Belanja',
                'topup'   => '💳 Top Up',
                'tarik'   => '💸 Tarik',
            ];
        @endphp
        <div class="flex overflow-x-auto gap-2 pb-1 mb-6 scrollbar-hide">
            @foreach($filters as $key => $label)
                <a href="{{ route('user.wallet.history', ['filter' => $key]) }}"
                   class="whitespace-nowrap text-xs font-bold px-4 py-2 rounded-full border transition-all
                          {{ $filter === $key
                             ? 'bg-[#0F2942] text-white border-[#0F2942]'
                             : 'bg-white text-gray-600 border-gray-200 hover:border-[#0F2942]/30 hover:text-[#0F2942]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Transaction List --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            @forelse($transactions as $tx)
                @php
                    $isIncome = $tx->is_income;
                    $iconMap = [
                        'topup'      => ['emoji' => '💳', 'bg' => 'bg-[#2DD4A0]/10'],
                        'purchase'   => ['emoji' => '🛍️', 'bg' => 'bg-red-50'],
                        'withdrawal' => ['emoji' => '💸', 'bg' => 'bg-orange-50'],
                        'reward'     => ['emoji' => '🎁', 'bg' => 'bg-purple-50'],
                        'credit'     => ['emoji' => '↩️', 'bg' => 'bg-blue-50'],
                        'recycle'    => ['emoji' => '♻️', 'bg' => 'bg-green-50'],
                    ];
                    $icon = $iconMap[$tx->type] ?? ['emoji' => '💰', 'bg' => 'bg-gray-50'];
                @endphp
                <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition">
                    <div class="w-11 h-11 rounded-2xl {{ $icon['bg'] }} flex items-center justify-center text-lg shrink-0">
                        {{ $icon['emoji'] }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-[#0F2942] text-sm truncate">{{ $tx->type_label }}</p>
                        @if($tx->description)
                            <p class="text-xs text-gray-400 truncate mt-0.5">{{ $tx->description }}</p>
                        @endif
                        <p class="text-[10px] text-gray-400 mt-1">{{ $tx->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>

                    <div class="text-right shrink-0">
                        <p class="font-black text-sm {{ $isIncome ? 'text-[#2DD4A0]' : 'text-[#0F2942]' }}">
                            {{ $isIncome ? '+' : '−' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </p>
                        <span class="inline-block mt-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $tx->status_color }}">
                            {{ $tx->status_label }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="text-5xl mb-4">📭</div>
                    <p class="font-bold text-gray-600">Tidak ada transaksi</p>
                    <p class="text-sm text-gray-400 mt-1">
                        @if($filter !== 'semua')
                            Tidak ada transaksi dengan filter ini.
                            <a href="{{ route('user.wallet.history') }}" class="text-[#D4AF37] underline">Lihat semua</a>
                        @else
                            Belum ada transaksi sama sekali.
                        @endif
                    </p>
                    @if($filter === 'semua')
                        <a href="{{ route('user.wallet.topup') }}" class="inline-block mt-4 bg-[#D4AF37] text-white font-bold px-6 py-2.5 rounded-xl text-sm hover:bg-[#b8952d] transition">
                            Top Up Pertama
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
