<x-admin-layout>
    <x-slot name="title">Dashboard Admin</x-slot>

    {{-- ═══ ROW 1: Stat Cards ═══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Pesanan --}}
        <a href="{{ route('admin.pesanan.index') }}" class="block glass-card p-5 md:p-8 rounded-3xl md:rounded-[2.5rem] border border-white/60 hover:-translate-y-2 transition-transform duration-300 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] bg-white/40 hover:bg-white/60 relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-24 h-24 md:w-32 md:h-32 bg-[var(--tx-primary-light)] rounded-full opacity-40 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 md:w-16 md:h-16 rounded-[1rem] md:rounded-[1.5rem] bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center text-xl md:text-3xl mb-4 md:mb-6 shadow-lg shadow-[var(--tx-primary)]/30 group-hover:scale-110 transition-transform">📦</div>
                <p class="text-[9px] md:text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-1 md:mb-2">Total Pesanan</p>
                <p class="text-4xl font-black text-[var(--tx-text-dark)]">{{ number_format($totalOrders) }}</p>
                <p class="text-xs text-[var(--tx-primary)] font-bold mt-3 bg-white/60 inline-flex px-3 py-1 rounded-full shadow-sm">↑ +{{ $todayOrders }} hari ini</p>
            </div>
        </a>

        {{-- Pesanan Pending --}}
        <a href="{{ route('admin.pesanan.index', ['status' => 'pending']) }}" class="block glass-card p-5 md:p-8 rounded-3xl md:rounded-[2.5rem] border border-white/60 hover:-translate-y-2 transition-transform duration-300 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] bg-white/40 hover:bg-white/60 relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-24 h-24 md:w-32 md:h-32 bg-amber-100 rounded-full opacity-40 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 md:w-16 md:h-16 rounded-[1rem] md:rounded-[1.5rem] bg-gradient-to-br from-amber-300 to-amber-500 text-white flex items-center justify-center text-xl md:text-3xl mb-4 md:mb-6 shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">⏳</div>
                <p class="text-[9px] md:text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-1 md:mb-2">Pesanan Pending</p>
                <p class="text-4xl font-black text-[var(--tx-text-dark)]">{{ number_format($pendingOrders) }}</p>
                <p class="text-xs text-amber-600 font-bold mt-3 bg-white/60 inline-flex px-3 py-1 rounded-full shadow-sm">⚠ Perlu diproses</p>
            </div>
        </a>

        {{-- Pendapatan (Solid Gradient) --}}
        <a href="{{ route('admin.financial.index') }}" class="block rounded-3xl md:rounded-[2.5rem] p-5 md:p-8 shadow-xl shadow-[var(--tx-primary)]/30 bg-gradient-to-br from-[var(--tx-primary)] via-[var(--tx-secondary)] to-[#7BB3E8] text-white relative overflow-hidden hover:-translate-y-2 transition-transform duration-300 group border border-white/40">
            <div class="absolute -right-10 -bottom-10 w-24 h-24 md:w-40 md:h-40 bg-white/30 rounded-full blur-2xl pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-[1rem] md:rounded-[1.5rem] bg-white/20 text-white flex items-center justify-center text-xl md:text-3xl mb-4 md:mb-6 backdrop-blur-md shadow-inner border border-white/30 group-hover:scale-110 transition-transform">💰</div>
                    <p class="text-[9px] md:text-[10px] font-black text-white/90 uppercase tracking-widest mb-1 md:mb-2">Pendapatan</p>
                    <p class="text-4xl font-black mb-2 tracking-tight">
                        @if($revenue >= 1000000)
                            <span class="text-2xl">Rp</span>{{ number_format($revenue / 1000000, 1) }}<span class="text-2xl">jt</span>
                        @else
                            <span class="text-2xl">Rp</span>{{ number_format($revenue, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
                <div class="mt-4 bg-white/20 backdrop-blur-md border border-white/30 inline-flex px-4 py-2 rounded-xl text-xs font-bold text-white shadow-sm self-start">
                    ↑ +Rp
                    @if($weekRevenue >= 1000000)
                        {{ number_format($weekRevenue / 1000000, 1) }}jt
                    @else
                        {{ number_format($weekRevenue, 0, ',', '.') }}
                    @endif
                    minggu ini
                </div>
            </div>
        </a>

        {{-- Ulasan Pending --}}
        <a href="{{ route('admin.ulasan.index', ['status' => 'pending']) }}" class="block glass-card p-5 md:p-8 rounded-3xl md:rounded-[2.5rem] border border-white/60 hover:-translate-y-2 transition-transform duration-300 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] bg-white/40 hover:bg-white/60 relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-24 h-24 md:w-32 md:h-32 bg-[var(--tx-tertiary-light)] rounded-full opacity-40 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 md:w-16 md:h-16 rounded-[1rem] md:rounded-[1.5rem] bg-gradient-to-br from-[var(--tx-tertiary)] to-[var(--tx-secondary)] text-white flex items-center justify-center text-xl md:text-3xl mb-4 md:mb-6 shadow-lg shadow-[var(--tx-tertiary)]/30 group-hover:scale-110 transition-transform">⭐</div>
                <p class="text-[9px] md:text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-1 md:mb-2">Ulasan Pending</p>
                <p class="text-4xl font-black text-[var(--tx-text-dark)]">{{ number_format($pendingReviews) }}</p>
                <p class="text-xs text-[var(--tx-text-muted)] font-bold mt-3 bg-white/60 inline-flex px-3 py-1 rounded-full shadow-sm">
                    {{ $pendingReviews === 0 ? '✅ Semua termoderasi' : '⚠ Menunggu moderasi' }}
                </p>
            </div>
        </a>
    </div>

    {{-- ═══ ROW 2: Stat Cards (Rewards) ═══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Total User --}}
        <a href="{{ route('admin.users.index') }}" class="block glass-card p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-white/60 hover:-translate-y-1 transition-transform bg-white/40 shadow-sm flex items-center gap-3 md:gap-4 group">
            <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-[1.25rem] bg-white border border-[var(--tx-primary)]/20 text-[var(--tx-primary)] flex items-center justify-center text-lg md:text-2xl shadow-sm shrink-0 group-hover:scale-110 transition-transform">👥</div>
            <div>
                <p class="text-[8px] md:text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-0.5 md:mb-1">Total User</p>
                <div class="flex items-end gap-2">
                    <p class="text-2xl font-black text-[var(--tx-text-dark)] leading-none">{{ number_format($totalUsers) }}</p>
                    <p class="text-[10px] text-[var(--tx-primary)] font-bold mb-0.5">↑ +{{ $newUsersThisWeek }}</p>
                </div>
            </div>
        </a>

        {{-- Produk Aktif --}}
        <a href="{{ route('admin.produk.index') }}" class="block glass-card p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-white/60 hover:-translate-y-1 transition-transform bg-white/40 shadow-sm flex items-center gap-3 md:gap-4">
            <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-[1.25rem] bg-white border border-[var(--tx-quaternary)]/20 text-[var(--tx-quaternary)] flex items-center justify-center text-lg md:text-2xl shadow-sm shrink-0">🧴</div>
            <div>
                <p class="text-[8px] md:text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-0.5 md:mb-1">Produk Aktif</p>
                <div class="flex items-end gap-2">
                    <p class="text-2xl font-black text-[var(--tx-text-dark)] leading-none">{{ number_format($activeProducts) }}</p>
                </div>
                @if($lowStockProducts > 0)
                    <p class="text-[9px] text-red-500 font-bold mt-1">⚠ {{ $lowStockProducts }} stok tipis</p>
                @endif
            </div>
        </a>

        {{-- Koin Beredar --}}
        <div class="glass-card p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-white/60 hover:-translate-y-1 transition-transform bg-white/40 shadow-sm flex items-center gap-3 md:gap-4">
            <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-[1.25rem] bg-white border border-[var(--tx-tertiary)]/20 text-[var(--tx-tertiary)] flex items-center justify-center text-lg md:text-2xl shadow-sm shrink-0">🪙</div>
            <div>
                <p class="text-[8px] md:text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-0.5 md:mb-1">Koin Beredar</p>
                <div class="flex items-end gap-2">
                    <p class="text-2xl font-black text-[var(--tx-text-dark)] leading-none">{{ number_format($totalCoins) }}</p>
                </div>
            </div>
        </div>

        {{-- Saldo Harvestly --}}
        <a href="{{ route('admin.wallets.index') }}" class="block glass-card p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-white/60 hover:-translate-y-1 transition-transform bg-white/40 shadow-sm flex items-center gap-3 md:gap-4 group">
            <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-[1.25rem] bg-white border border-[var(--tx-secondary)]/20 text-[var(--tx-secondary)] flex items-center justify-center text-lg md:text-2xl shadow-sm shrink-0 group-hover:scale-110 transition-transform">💳</div>
            <div>
                <p class="text-[8px] md:text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-0.5 md:mb-1">Saldo User</p>
                <div class="flex items-end gap-2">
                    <p class="text-xl font-black text-[var(--tx-text-dark)] leading-none">
                        @if($totalHarvestly >= 1000000)
                            {{ number_format($totalHarvestly / 1000000, 1) }}jt
                        @else
                            {{ number_format($totalHarvestly, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
            </div>
        </a>
    </div>

    {{-- ═══ ROW 3: Chart & Status ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        {{-- Chart Pendapatan 7 Hari --}}
        <div class="lg:col-span-2 glass-card rounded-[2.5rem] border border-white/60 p-8 md:p-10 flex flex-col shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] bg-white/50">
            <h3 class="font-black text-[var(--tx-text-dark)] text-2xl mb-2">Pendapatan 7 Hari Terakhir</h3>
            <p class="text-sm font-bold text-[var(--tx-text-muted)] mb-10">Total transaksi berhasil per hari</p>
            
            <div class="flex items-end gap-3 md:gap-6 h-56 mt-auto px-2">
                @php $maxVal = max(array_merge($dailyRevenue, [1])); @endphp
                @foreach($dailyRevenue as $i => $val)
                    @php $pct = max(5, round(($val / $maxVal) * 100)); @endphp
                    <div class="flex-1 flex flex-col items-center group h-full">
                        <div class="flex-1 w-full flex flex-col justify-end items-center gap-2 mb-3">
                            <span class="text-[11px] bg-white border border-gray-100 shadow-sm px-3 py-1 rounded-lg text-[var(--tx-text-dark)] font-black opacity-0 group-hover:opacity-100 transition-opacity translate-y-2 group-hover:-translate-y-2 duration-300">
                                @if($val >= 1000000) {{ round($val/1000000, 1) }}jt @elseif($val >= 1000) {{ round($val/1000) }}k @else {{ $val }} @endif
                            </span>
                            <div class="w-full rounded-[14px] bg-gradient-to-t from-[var(--tx-primary)] via-[var(--tx-secondary)] to-[var(--tx-tertiary)] opacity-60 group-hover:opacity-100 transition-all duration-500 shadow-lg border border-white/60 relative overflow-hidden" style="height: {{ $pct }}%">
                                <div class="absolute inset-0 bg-white/20 group-hover:animate-pulse"></div>
                            </div>
                        </div>
                        <span class="text-[10px] md:text-xs text-[var(--tx-text-dark)] font-black uppercase tracking-widest">{{ $dailyLabels[$i] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Donut Chart Status --}}
        <div class="glass-card rounded-[2.5rem] border border-white/60 p-8 md:p-10 flex flex-col items-center justify-center shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)] bg-white/50">
            <div class="w-full text-left mb-8">
                <h3 class="font-black text-[var(--tx-text-dark)] text-2xl mb-2">Status Pesanan</h3>
                <p class="text-sm font-bold text-[var(--tx-text-muted)]">Distribusi status saat ini</p>
            </div>
            
            @php
                $totalTx = array_sum($statusCounts);
                $donutColors = [
                    'pending'    => ['stroke' => '#F472B6', 'label' => 'Pending',    'bg' => 'bg-[var(--tx-secondary)]'],
                    'diproses'   => ['stroke' => '#4A90D9', 'label' => 'Diproses',   'bg' => 'bg-[var(--tx-primary)]'],
                    'dikirim'    => ['stroke' => '#7BB3E8', 'label' => 'Dikirim',    'bg' => 'bg-[var(--tx-primary-mid)]'],
                    'selesai'    => ['stroke' => '#6BAE9B', 'label' => 'Selesai',    'bg' => 'bg-[var(--tx-quaternary)]'],
                    'dibatalkan' => ['stroke' => '#EF4444', 'label' => 'Dibatalkan', 'bg' => 'bg-red-500'],
                ];
                $r = 65; $cx = 85; $cy = 85; $circ = 2 * M_PI * $r;
                $offset = 0; $segments = [];
                foreach ($donutColors as $key => $meta) {
                    $count = $statusCounts[$key] ?? 0;
                    $dash = $totalTx > 0 ? ($count / $totalTx) * $circ : 0;
                    $segments[] = ['dash' => $dash, 'offset' => $offset, 'color' => $meta['stroke'], 'count' => $count, 'label' => $meta['label'], 'bg' => $meta['bg']];
                    $offset += $dash;
                }
            @endphp
            <div class="relative w-[170px] h-[170px] mb-10 drop-shadow-xl">
                <svg width="170" height="170" viewBox="0 0 170 170" class="transform -rotate-90">
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="20"/>
                    @foreach($segments as $seg)
                        @if($seg['dash'] > 0)
                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                            stroke="{{ $seg['color'] }}" stroke-width="20"
                            stroke-dasharray="{{ round($seg['dash'], 2) }} {{ round($circ - $seg['dash'], 2) }}"
                            stroke-dashoffset="{{ round(-$seg['offset'], 2) }}"
                            class="transition-all duration-1000 ease-out"/>
                        @endif
                    @endforeach
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/50 rounded-full m-8 backdrop-blur-md border border-white shadow-inner">
                    <span class="text-4xl font-black text-[var(--tx-text-dark)] leading-none">{{ $totalTx }}</span>
                    <span class="text-[10px] font-black text-[var(--tx-text-muted)] mt-1 uppercase tracking-widest">Total</span>
                </div>
            </div>

            <div class="w-full space-y-3">
                @foreach($segments as $seg)
                @if($seg['count'] > 0)
                <div class="flex items-center justify-between text-sm bg-white/70 backdrop-blur-md px-5 py-3.5 rounded-2xl border border-white shadow-sm hover:scale-105 transition-transform cursor-default">
                    <div class="flex items-center gap-3">
                        <span class="w-4 h-4 rounded-full {{ $seg['bg'] }} shadow-md"></span>
                        <span class="text-[var(--tx-text-dark)] font-black text-xs uppercase tracking-widest">{{ $seg['label'] }}</span>
                    </div>
                    <span class="font-black text-[var(--tx-text-dark)] text-base">{{ $seg['count'] }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ ROW 4: Produk Terlaris & Pesanan Terbaru ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-10">
        {{-- Produk Terlaris --}}
        <div class="glass-card rounded-[2.5rem] border border-white/60 flex flex-col overflow-hidden bg-white/40 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)]">
            <div class="px-8 py-6 border-b border-white/60 flex justify-between items-center bg-white/50">
                <h3 class="font-black text-[var(--tx-text-dark)] text-xl">🏆 Produk Terlaris</h3>
                <a href="{{ route('admin.produk.index') }}" class="text-[10px] font-black text-[var(--tx-primary)] hover:text-white hover:bg-[var(--tx-primary)] transition-all bg-white border border-white shadow-sm px-5 py-2 rounded-full uppercase tracking-widest">Semua</a>
            </div>
            <div class="divide-y divide-white/40 p-4">
                @forelse($topProducts as $i => $product)
                <div class="px-5 py-5 flex items-center gap-5 hover:bg-white/60 transition-colors rounded-3xl group">
                    <span class="text-2xl font-black text-[var(--tx-tertiary)] w-8 text-center group-hover:scale-125 transition-transform">{{ $i + 1 }}</span>
                    <div class="w-16 h-16 rounded-[1.25rem] bg-white border border-gray-100 flex items-center justify-center text-3xl overflow-hidden shrink-0 shadow-sm p-1">
                        @php $imgs = is_string($product->images) ? json_decode($product->images, true) : $product->images; @endphp
                        @if($imgs && count($imgs) > 0)
                            <img src="{{ Storage::url($imgs[0]) }}" alt="" class="w-full h-full object-cover rounded-[14px]">
                        @else
                            🧴
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-[var(--tx-text-dark)] text-base truncate">{{ $product->name }}</p>
                        <p class="text-[10px] font-bold text-[var(--tx-text-muted)] mt-1.5 bg-white/80 border border-white shadow-sm inline-block px-3 py-1 rounded-md uppercase tracking-widest">{{ number_format($product->total_sold) }} terjual</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[9px] font-black text-[var(--tx-text-muted)] mb-1 uppercase tracking-widest">Pendapatan</p>
                        <p class="text-base font-black text-[var(--tx-primary)]">
                            @if($product->total_revenue >= 1000000)
                                Rp {{ number_format($product->total_revenue / 1000000, 1) }}jt
                            @else
                                Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                            @endif
                        </p>
                    </div>
                </div>
                @empty
                <div class="px-8 py-16 text-center">
                    <span class="text-4xl opacity-50 mb-4 block">📈</span>
                    <div class="font-black text-[var(--tx-text-muted)] text-sm uppercase tracking-widest">Belum ada data penjualan</div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pesanan Terbaru --}}
        <div class="glass-card rounded-[2.5rem] border border-white/60 flex flex-col overflow-hidden bg-white/40 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)]">
            <div class="px-8 py-6 border-b border-white/60 flex justify-between items-center bg-white/50">
                <h3 class="font-black text-[var(--tx-text-dark)] text-xl">📋 Pesanan Terbaru</h3>
                <a href="{{ route('admin.pesanan.index') }}" class="text-[10px] font-black text-[var(--tx-primary)] hover:text-white hover:bg-[var(--tx-primary)] transition-all bg-white border border-white shadow-sm px-5 py-2 rounded-full uppercase tracking-widest">Semua</a>
            </div>
            <div class="divide-y divide-white/40 p-4">
                @forelse($recentOrders as $order)
                @php
                    $statusMap = [
                        'pending'    => ['bg' => 'bg-pink-50 text-[var(--tx-secondary)] border-pink-100', 'label' => 'Pending'],
                        'diproses'   => ['bg' => 'bg-blue-50 text-[var(--tx-primary)] border-blue-100',   'label' => 'Diproses'],
                        'dikirim'    => ['bg' => 'bg-blue-50/50 text-[var(--tx-primary-mid)] border-blue-100/50',   'label' => 'Dikirim'],
                        'selesai'    => ['bg' => 'bg-emerald-50 text-[var(--tx-quaternary)] border-emerald-100', 'label' => 'Selesai'],
                        'dibatalkan' => ['bg' => 'bg-red-50 text-red-500 border-red-100',     'label' => 'Batal'],
                    ];
                    $s = $statusMap[$order->status] ?? ['bg' => 'bg-white/80 text-gray-500 border-white', 'label' => $order->status];
                @endphp
                <a href="{{ route('admin.pesanan.show', $order) }}" class="px-5 py-5 flex items-center gap-5 hover:bg-white/60 transition-all rounded-3xl group block shadow-sm border border-transparent hover:border-white/80">
                    <div class="w-14 h-14 rounded-[1.25rem] bg-white border border-white flex items-center justify-center text-2xl shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                        👤
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2.5">
                            <p class="font-black text-[var(--tx-text-dark)] text-base truncate group-hover:text-[var(--tx-primary)] transition-colors">{{ $order->user->name }}</p>
                            <span class="px-3 py-1 rounded-md text-[9px] font-black border {{ $s['bg'] }} uppercase tracking-widest shadow-sm">{{ $s['label'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold text-[var(--tx-text-muted)] bg-white/60 border border-white/80 px-4 py-2 rounded-xl shadow-inner">
                            <span class="font-mono tracking-widest text-[var(--tx-primary)]">{{ $order->receipt_number }}</span>
                            <span class="font-black text-[var(--tx-text-dark)]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="px-8 py-16 text-center">
                    <span class="text-4xl opacity-50 mb-4 block">💤</span>
                    <div class="font-black text-[var(--tx-text-muted)] text-sm uppercase tracking-widest">Belum ada pesanan terbaru</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
