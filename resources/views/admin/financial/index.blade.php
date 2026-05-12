<x-admin-layout>
    <x-slot name="title">Laporan Keuangan</x-slot>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-[var(--tx-text-dark)] mb-1">Dashboard Keuangan 📊</h2>
            <p class="text-[var(--tx-text-muted)] font-bold text-sm">Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('admin.financial.index') }}" method="GET" class="flex items-center gap-2 bg-white/60 backdrop-blur-md p-2 rounded-2xl border border-white shadow-sm">
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-transparent border-none text-xs font-bold focus:ring-0">
                <span class="text-gray-400">to</span>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-transparent border-none text-xs font-bold focus:ring-0">
                <button type="submit" class="bg-[var(--tx-primary)] text-white p-2 rounded-xl hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>

            <a href="{{ route('admin.financial.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn-gradient px-6 py-3 rounded-2xl flex items-center gap-2 shadow-lg shadow-pink-500/20">
                <span class="text-lg">📥</span>
                <span class="text-xs uppercase tracking-widest font-black">Export Excel</span>
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Total Pemasukan --}}
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/60 relative overflow-hidden group bg-white/40 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)]">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-100 rounded-full opacity-40 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[var(--tx-primary)] to-blue-600 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-blue-500/20">📈</div>
                <p class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2">Total Pemasukan</p>
                <p class="text-3xl font-black text-[var(--tx-text-dark)]">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                <div class="mt-4 flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black {{ $incomeChange >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $incomeChange >= 0 ? '↑' : '↓' }} {{ number_format(abs($incomeChange), 1) }}%
                    </span>
                    <span class="text-[10px] font-bold text-gray-400 italic">vs bulan lalu</span>
                </div>
            </div>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/60 relative overflow-hidden group bg-white/40 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)]">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-pink-100 rounded-full opacity-40 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[var(--tx-secondary)] to-red-500 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-pink-500/20">📉</div>
                <p class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2">Total Pengeluaran</p>
                <p class="text-3xl font-black text-[var(--tx-text-dark)]">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                <a href="{{ route('admin.financial.expenses') }}" class="mt-4 inline-flex text-[10px] font-black text-[var(--tx-secondary)] uppercase tracking-widest hover:underline transition-all">Lihat Detail →</a>
            </div>
        </div>

        {{-- Laba Bersih --}}
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/60 relative overflow-hidden group bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-xl shadow-pink-500/10">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/20 rounded-full blur-3xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl mb-6 border border-white/30 shadow-inner">💰</div>
                <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-2">Laba Bersih</p>
                <p class="text-3xl font-black">Rp {{ number_format($netProfit, 0, ',', '.') }}</p>
                <p class="mt-4 text-[10px] font-bold text-white/60 italic">Estimasi keuntungan periode ini</p>
            </div>
        </div>

        {{-- Jumlah Transaksi --}}
        <div class="glass-card p-8 rounded-[2.5rem] border border-white/60 relative overflow-hidden group bg-white/40 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.05)]">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-purple-100 rounded-full opacity-40 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[var(--tx-tertiary)] to-purple-600 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-purple-500/20">📦</div>
                <p class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2">Transaksi Berhasil</p>
                <p class="text-3xl font-black text-[var(--tx-text-dark)]">{{ number_format($totalTransactions) }}</p>
                <p class="mt-4 text-[10px] font-bold text-green-600 font-black">Suksess Terverifikasi</p>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        {{-- Trend Chart --}}
        <div class="lg:col-span-2 glass-card p-8 md:p-10 rounded-[3rem] border border-white/80 bg-white/40 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="font-black text-[var(--tx-text-dark)] text-2xl mb-1">Tren Keuangan</h3>
                    <p class="text-sm font-bold text-[var(--tx-text-muted)]">Perbandingan 6 bulan terakhir</p>
                </div>
            </div>
            <div class="h-[350px] w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Breakdown Chart --}}
        <div class="glass-card p-8 md:p-10 rounded-[3rem] border border-white/80 bg-white/40 shadow-sm flex flex-col">
            <div class="mb-10">
                <h3 class="font-black text-[var(--tx-text-dark)] text-2xl mb-1">Pengeluaran</h3>
                <p class="text-sm font-bold text-[var(--tx-text-muted)]">Breakdown per kategori</p>
            </div>
            <div class="flex-1 flex flex-col items-center justify-center relative">
                <div class="w-full max-w-[250px] aspect-square mb-10">
                    <canvas id="breakdownChart"></canvas>
                </div>
                
                <div class="w-full space-y-3">
                    @foreach($expenseBreakdown as $cat => $amount)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full 
                                    @if($cat == 'stok') bg-blue-500 @elseif($cat == 'operasional') bg-pink-500 @elseif($cat == 'gaji') bg-purple-500 @elseif($cat == 'marketing') bg-amber-500 @else bg-gray-400 @endif
                                "></span>
                                <span class="text-xs font-black text-[var(--tx-text-muted)] uppercase tracking-widest">{{ ucfirst($cat) }}</span>
                            </div>
                            <span class="text-xs font-black text-[var(--tx-text-dark)]">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: {!! json_encode($chartIncome) !!},
                        borderColor: '#4A90D9',
                        backgroundColor: 'rgba(74, 144, 217, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 6,
                        pointBackgroundColor: '#4A90D9',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($chartExpenses) !!},
                        borderColor: '#F472B6',
                        backgroundColor: 'rgba(244, 114, 182, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 6,
                        pointBackgroundColor: '#F472B6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: 'Plus Jakarta Sans', weight: '800', size: 12 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: '700' },
                            callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', weight: '700' } }
                    }
                }
            }
        });

        // Breakdown Chart
        const breakdownCtx = document.getElementById('breakdownChart').getContext('2d');
        new Chart(breakdownCtx, {
            type: 'doughnut',
            data: {
                labels: ['Stok', 'Operasional', 'Gaji', 'Marketing', 'Lain-lain'],
                datasets: [{
                    data: {!! json_encode(array_values($expenseBreakdown)) !!},
                    backgroundColor: ['#4A90D9', '#F472B6', '#9B8EC4', '#FBBF24', '#94A3B8'],
                    borderWidth: 5,
                    borderColor: '#fff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>
