<x-admin-layout>
    <x-slot name="title">Rekap Bulanan</x-slot>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-[var(--tx-text-dark)] mb-1">Arsip & Rekap Bulanan 📂</h2>
            <p class="text-[var(--tx-text-muted)] font-bold text-sm">Rekaman performa bisnis Trinexa per bulan</p>
        </div>
        
        <form action="{{ route('admin.financial.recap') }}" method="GET" class="flex items-center gap-2 bg-white/60 backdrop-blur-md p-2 rounded-2xl border border-white shadow-sm">
            <input type="month" name="month" value="{{ $month->format('Y-m') }}" class="bg-transparent border-none text-xs font-black focus:ring-0">
            <button type="submit" class="bg-[var(--tx-primary)] text-white px-4 py-2 rounded-xl hover:scale-105 transition-transform text-xs font-black uppercase tracking-widest">
                Tampilkan
            </button>
        </form>
    </div>

    {{-- Top Row: Monthly Summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        {{-- Best Sellers --}}
        <div class="lg:col-span-1 glass-card p-8 rounded-[2.5rem] border border-white/80 bg-white/40 shadow-sm">
            <h3 class="font-black text-[var(--tx-text-dark)] text-xl mb-6 flex items-center gap-3">
                <span class="text-2xl">🔥</span> Top 5 Produk Terlaris
            </h3>
            
            <div class="space-y-6">
                @forelse($topProducts as $item)
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-xl bg-white/60 border border-gray-100 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                            <img src="{{ $item->product->primary_image }}" alt="" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-black text-[var(--tx-text-dark)] line-clamp-1 group-hover:text-[var(--tx-primary)] transition-colors">{{ $item->product->name }}</p>
                            <p class="text-[10px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">{{ $item->total_sold }} terjual</p>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-xs font-bold text-gray-400 italic">Data belum tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Monthly Archive Table --}}
        <div class="lg:col-span-2 glass-card rounded-[2.5rem] border border-white/80 bg-white/40 shadow-sm overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-gray-50 bg-white/40">
                <h3 class="font-black text-[var(--tx-text-dark)] text-xl">Arsip Riwayat Keuangan</h3>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-4 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Bulan</th>
                            <th class="px-8 py-4 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest text-right">Pemasukan</th>
                            <th class="px-8 py-4 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest text-right">Pengeluaran</th>
                            <th class="px-8 py-4 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest text-right">Laba Bersih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($archive as $item)
                            <tr class="hover:bg-white/60 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="text-sm font-black text-[var(--tx-text-dark)]">{{ Carbon\Carbon::parse($item->month_year)->format('F Y') }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-sm font-bold text-green-600">Rp {{ number_format($item->income, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-sm font-bold text-red-500">Rp {{ number_format($item->total_expenses, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-sm font-black text-[var(--tx-text-dark)]">Rp {{ number_format($item->profit, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
