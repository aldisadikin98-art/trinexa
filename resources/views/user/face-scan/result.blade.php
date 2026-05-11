<x-app-layout>
    <x-slot name="title">Hasil Face AI Scan | Trinexa</x-slot>

    <div class="py-12 min-h-screen relative z-10">

        <!-- Ambient Orbs -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-gradient-to-bl from-[var(--tx-pink)]/60 to-[var(--tx-secondary-light)] rounded-full blur-[100px] opacity-60 pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-80 h-80 bg-gradient-to-tr from-[var(--tx-primary-light)] to-[var(--tx-tertiary-light)] rounded-full blur-[100px] opacity-50 pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Back -->
            <a href="{{ route('user.face-scan.index') }}" class="inline-flex items-center gap-2 text-sm font-black text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] mb-8 transition-all glass-card px-4 py-2 rounded-full border border-white/60 bg-white/40 shadow-sm hover:scale-105 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Scan Ulang
            </a>

            <!-- Title -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 glass-card bg-green-50/80 border border-green-200/60 px-5 py-2.5 rounded-full text-green-600 font-black text-[10px] uppercase tracking-widest mb-4 shadow-sm animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Analisis Selesai
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-[var(--tx-text-dark)] mb-3 leading-tight">
                    Hasil <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)]">Scan Kulit</span>
                </h1>
                <p class="text-[var(--tx-text-muted)] font-medium">Berikut adalah hasil analisis AI untuk kondisi kulitmu</p>
            </div>

            @php
                $resultJson = is_array($history->result_json) ? $history->result_json : json_decode($history->result_json, true);
                $skinType = $history->tipe_kulit ?? $resultJson['skin_type'] ?? 'Kombinasi';
                $oily = $resultJson['oily'] ?? '0%';
                $dry  = $resultJson['dry']  ?? '0%';
                $acne = $resultJson['acne'] ?? '0%';

                // Parse numbers
                $oilyNum = (int) filter_var($oily, FILTER_SANITIZE_NUMBER_INT);
                $dryNum  = (int) filter_var($dry,  FILTER_SANITIZE_NUMBER_INT);
                $acneNum = (int) filter_var($acne, FILTER_SANITIZE_NUMBER_INT);

                $skinTypeConfig = [
                    'Berminyak' => ['color' => 'from-amber-400 to-orange-500', 'icon' => '🌊', 'bg' => 'bg-amber-50'],
                    'Kering'    => ['color' => 'from-blue-400 to-blue-600',    'icon' => '🏜️', 'bg' => 'bg-blue-50'],
                    'Kombinasi' => ['color' => 'from-[var(--tx-primary)] to-[var(--tx-secondary)]', 'icon' => '⚖️', 'bg' => 'bg-[var(--tx-primary-light)]'],
                    'Normal'    => ['color' => 'from-green-400 to-emerald-500','icon' => '✨', 'bg' => 'bg-green-50'],
                    'Sensitif'  => ['color' => 'from-rose-400 to-pink-500',    'icon' => '🌸', 'bg' => 'bg-rose-50'],
                ];
                $stc = $skinTypeConfig[$skinType] ?? $skinTypeConfig['Kombinasi'];

                $tips = [
                    'Berminyak' => ['Gunakan pembersih berbasis gel', 'Pakai toner dengan kandungan niacinamide', 'Hindari pelembap berminyak', 'Gunakan SPF 30+ oil-free setiap hari'],
                    'Kering'    => ['Gunakan pembersih cream yang lembut', 'Pakai serum hyaluronic acid', 'Gunakan pelembap kental di malam hari', 'Hindari air panas saat mencuci muka'],
                    'Kombinasi' => ['Gunakan produk yang ringan di T-zone', 'Double cleansing di malam hari', 'Gunakan pelembap yang seimbang', 'Masker clay 1x seminggu di T-zone'],
                    'Normal'    => ['Pertahankan rutinitas CTM dasar', 'Gunakan SPF setiap hari', 'Eksfoliasi lembut 1-2x seminggu', 'Konsumsi air minimal 8 gelas sehari'],
                    'Sensitif'  => ['Gunakan produk fragrance-free', 'Patch test sebelum coba produk baru', 'Gunakan pelembap dengan ceramide', 'Hindari eksfoliasi berlebihan'],
                ];
                $currentTips = $tips[$skinType] ?? $tips['Kombinasi'];
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

                <!-- Foto Wajah -->
                <div class="glass-card bg-white/50 rounded-[2.5rem] border border-white/70 p-6 shadow-lg backdrop-blur-xl">
                    <h2 class="text-sm font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-5 flex items-center gap-2">
                        <span class="text-lg">📷</span> Foto Wajah
                    </h2>
                    <div class="relative rounded-[1.75rem] overflow-hidden aspect-square border-4 border-white shadow-inner">
                        <img src="{{ $history->foto_url }}" alt="Face Scan" class="w-full h-full object-cover">
                        <!-- Scan line animation overlay -->
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-[var(--tx-primary)] to-transparent opacity-60 animate-[scanline_3s_ease-in-out_infinite]"></div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-center gap-2 bg-white/50 border border-white/60 rounded-xl px-4 py-2.5 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Analisis Berhasil</span>
                    </div>
                </div>

                <!-- Tipe Kulit & Metrik -->
                <div class="space-y-5">

                    <!-- Tipe Kulit Card -->
                    <div class="glass-card bg-gradient-to-br {{ $stc['color'] }} rounded-[2.5rem] p-8 border border-white/20 text-white shadow-xl relative overflow-hidden group">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                        <p class="text-white/70 text-[10px] font-black uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-white"></span> Tipe Kulit Terdeteksi
                        </p>
                        <div class="flex items-center gap-4 relative z-10">
                            <span class="text-5xl drop-shadow-sm">{{ $stc['icon'] }}</span>
                            <div>
                                <h2 class="text-3xl font-black drop-shadow-sm">{{ $skinType }}</h2>
                                <p class="text-white/80 text-xs font-bold uppercase tracking-widest">Kulit {{ $skinType }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Metrik Cards -->
                    @foreach([
                        ['🌊', 'Kadar Minyak (Oily)', $oily, $oilyNum, 'from-amber-400 to-orange-400'],
                        ['💧', 'Kadar Kering (Dry)',   $dry,  $dryNum,  'from-blue-400 to-blue-500'],
                        ['🔴', 'Jerawat (Acne)',        $acne, $acneNum, 'from-red-400 to-rose-400'],
                    ] as [$icon, $label, $value, $num, $color])
                    <div class="glass-card bg-white/50 rounded-[1.5rem] border border-white/70 px-6 py-5 shadow-sm backdrop-blur-xl">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">{{ $icon }}</span>
                                <span class="text-xs font-black text-[var(--tx-text-dark)] uppercase tracking-widest">{{ $label }}</span>
                            </div>
                            <span class="font-black text-xl text-transparent bg-clip-text bg-gradient-to-r {{ $color }} drop-shadow-sm">{{ $value }}</span>
                        </div>
                        <div class="w-full bg-black/5 rounded-full h-2.5 overflow-hidden shadow-inner border border-white/40">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $color }} transition-all duration-1000" style="width: {{ min($num, 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tips & Rekomendasi -->
            <div class="glass-card bg-white/50 rounded-[2.5rem] border border-white/70 p-8 shadow-lg backdrop-blur-xl mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] flex items-center justify-center text-xl shadow-lg shadow-[var(--tx-primary)]/20">💡</div>
                    <div>
                        <h2 class="text-xl font-black text-[var(--tx-text-dark)]">Rekomendasi Perawatan</h2>
                        <p class="text-[10px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Khusus untuk kulit {{ $skinType }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($currentTips as $i => $tip)
                    <div class="flex items-center gap-4 bg-white/60 border border-white/70 rounded-2xl px-5 py-4 shadow-sm hover:-translate-y-1 transition-transform">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white font-black text-sm flex items-center justify-center shrink-0 shadow-inner">{{ $i + 1 }}</div>
                        <p class="text-sm font-bold text-[var(--tx-text-dark)]">{{ $tip }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('dermatology.index', ['skin_type' => strtolower($skinType)]) }}"
                   class="flex-1 text-center py-5 rounded-[1.5rem] font-black text-white bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] hover:-translate-y-1 transition-all shadow-lg shadow-[var(--tx-primary)]/30 uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                    <span>📚</span> Lihat Artikel untuk Kulit {{ $skinType }}
                </a>
                <a href="{{ route('user.face-scan.index') }}"
                   class="flex-1 text-center py-5 rounded-[1.5rem] font-black text-[var(--tx-text-muted)] bg-white/60 border border-white/70 hover:bg-white/80 hover:text-[var(--tx-primary)] transition-all shadow-sm uppercase tracking-widest text-xs">
                    🔄 Scan Lagi
                </a>
            </div>

        </div>
    </div>

    <style>
        @keyframes scanline {
            0% { top: 0%; opacity: 1; }
            50% { top: 100%; opacity: 0.3; }
            100% { top: 0%; opacity: 1; }
        }
    </style>
</x-app-layout>
