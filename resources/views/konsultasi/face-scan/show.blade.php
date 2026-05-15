<x-app-layout>
    <x-slot name="title">Hasil Face Scan | Truevera Trinexa</x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-3 text-xs font-black uppercase tracking-widest text-[var(--tx-text-muted)] mb-6">
            <a href="{{ route('konsultasi.index') }}" class="hover:text-[var(--tx-primary)] transition-colors">Konsultasi</a>
            <span class="opacity-50">/</span>
            <a href="{{ route('konsultasi.face-scan.index') }}" class="hover:text-[var(--tx-primary)] transition-colors">Face Scan</a>
            <span class="opacity-50">/</span>
            <span class="text-[var(--tx-text-dark)]">Hasil Analisis</span>
        </nav>

        {{-- TOP SUMMARY CARD --}}
        <div class="glass-card border border-white/50 relative overflow-hidden mb-8 shadow-xl">
            <div class="absolute inset-0 pointer-events-none opacity-20" style="background: radial-gradient(circle at 80% 50%, var(--tx-secondary) 0%, transparent 55%), radial-gradient(circle at 20% 50%, var(--tx-primary) 0%, transparent 55%);"></div>
            <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-center gap-8">

                {{-- Foto --}}
                <div class="shrink-0 w-40 h-40 rounded-[24px] overflow-hidden border-4 border-white/80 shadow-xl">
                    <img src="{{ Str::startsWith($result->photo_path, 'data:image') ? $result->photo_path : Storage::url($result->photo_path) }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/160'">
                </div>

                {{-- Score ring --}}
                <div class="shrink-0 text-center">
                    <div class="relative w-36 h-36">
                        <svg class="w-36 h-36 -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="50" stroke="rgba(255,255,255,0.3)" stroke-width="10" fill="none"/>
                            <circle cx="60" cy="60" r="50" stroke="url(#scoreGrad)" stroke-width="10" fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 50 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 50 * (1 - $result->skin_score / 100) }}"
                                stroke-linecap="round"/>
                            <defs>
                                <linearGradient id="scoreGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#4A90D9"/>
                                    <stop offset="100%" stop-color="#F472B6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-black text-[var(--tx-text-dark)] drop-shadow-sm">{{ $result->skin_score }}</span>
                            <span class="text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">/100</span>
                        </div>
                    </div>
                    <p class="text-xs font-black text-[var(--tx-text-muted)] uppercase tracking-widest mt-2">Skin Score</p>
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 bg-white/40 border border-white px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest text-[var(--tx-text-dark)] mb-3">
                        📸 Analisis {{ $result->created_at->translatedFormat('d M Y, H:i') }}
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black text-[var(--tx-text-dark)] mb-2">Kulit {{ $result->skin_type }}</h1>
                    <p class="text-lg font-black text-[var(--tx-secondary)] mb-4">{{ $result->score_label }}</p>
                    <p class="text-sm font-bold text-[var(--tx-text-muted)] leading-relaxed max-w-md">{{ $result->summary }}</p>
                </div>

                {{-- Actions --}}
                <div class="shrink-0 flex flex-col gap-3">
                    <a href="{{ route('konsultasi.face-scan.index') }}" class="btn-gradient gap-2 text-sm">📸 Scan Ulang</a>
                    <a href="{{ route('konsultasi.chat.index') }}" class="btn-outline-white gap-2 text-sm">💬 Tanya Truevera</a>
                    <button onclick="deleteScan()" class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors py-2 border border-red-200 rounded-[12px] bg-red-50/50 hover:bg-red-50">🗑️ Hapus</button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            {{-- Kondisi Kulit --}}
            <div class="lg:col-span-2 glass-card border border-white/50 p-8">
                <h2 class="font-black text-[var(--tx-text-dark)] text-lg mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-[12px] bg-[var(--tx-primary-light)] flex items-center justify-center border border-white shadow-sm">🔬</span>
                    Kondisi Kulit
                </h2>
                <div class="space-y-5">
                    @foreach($result->conditions as $cond)
                    @php
                        $statusColor = match($cond['status'] ?? 'cukup') {
                            'baik'   => ['bg' => 'bg-[var(--tx-quaternary-light)]', 'text' => 'text-[var(--tx-quaternary)]', 'dot' => 'bg-[var(--tx-quaternary)]', 'bar' => 'from-[var(--tx-quaternary)] to-[var(--tx-quaternary-mid)]', 'pct' => 90],
                            'cukup'  => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'dot' => 'bg-amber-400', 'bar' => 'from-amber-400 to-amber-500', 'pct' => 60],
                            default  => ['bg' => 'bg-red-50', 'text' => 'text-red-500', 'dot' => 'bg-red-400', 'bar' => 'from-red-400 to-red-500', 'pct' => 30],
                        };
                        $icons = ['Hidrasi' => '💧', 'Pori-pori' => '🔎', 'Produksi Minyak' => '✨', 'Elastisitas' => '🌿', 'Hiperpigmentasi' => '🎨'];
                    @endphp
                    <div class="bg-white/30 border border-white/60 rounded-[16px] p-4 backdrop-blur-sm hover:-translate-y-0.5 transition-transform">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">{{ $icons[$cond['name']] ?? '🔬' }}</span>
                                <span class="font-black text-[var(--tx-text-dark)] text-sm">{{ $cond['name'] }}</span>
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-white shadow-sm {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                                {{ ucfirst($cond['status']) }}
                            </span>
                        </div>
                        <div class="bg-white/40 rounded-full h-2 overflow-hidden mb-2 border border-white/60">
                            <div class="h-2 rounded-full bg-gradient-to-r {{ $statusColor['bar'] }} transition-all duration-1000" style="width: {{ $statusColor['pct'] }}%"></div>
                        </div>
                        <p class="text-xs font-bold text-[var(--tx-text-muted)] leading-relaxed">{{ $cond['detail'] ?? '' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tips --}}
            <div class="glass-card border border-white/50 p-6">
                <h2 class="font-black text-[var(--tx-text-dark)] text-base mb-5 flex items-center gap-3">
                    <span class="w-9 h-9 rounded-[10px] bg-[var(--tx-secondary-light)] flex items-center justify-center border border-white shadow-sm text-lg">💡</span>
                    Tips untuk Kamu
                </h2>
                <div class="space-y-3">
                    @foreach($result->tips as $i => $tip)
                    <div class="flex items-start gap-3 bg-white/30 border border-white/50 rounded-[12px] p-3">
                        <span class="w-6 h-6 rounded-full bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white text-[10px] font-black flex items-center justify-center shrink-0 border border-white shadow-sm">{{ $i + 1 }}</span>
                        <p class="text-xs font-bold text-[var(--tx-text-dark)] leading-relaxed">{{ $tip }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

            {{-- Rutinitas Pagi --}}
            <div class="glass-card border border-white/50 p-6">
                <h3 class="font-black text-[var(--tx-text-dark)] text-base mb-5 flex items-center gap-2">
                    <span class="text-2xl">🌅</span> Rutinitas Pagi
                </h3>
                <ol class="space-y-3">
                    @foreach($result->morning_routine as $i => $step)
                    <li class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[var(--tx-primary-light)] text-[var(--tx-primary)] text-[10px] font-black flex items-center justify-center shrink-0 border border-white shadow-sm">{{ $i+1 }}</span>
                        <span class="text-sm font-bold text-[var(--tx-text-dark)]">{{ $step }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>

            {{-- Rutinitas Malam --}}
            <div class="glass-card border border-white/50 p-6">
                <h3 class="font-black text-[var(--tx-text-dark)] text-base mb-5 flex items-center gap-2">
                    <span class="text-2xl">🌙</span> Rutinitas Malam
                </h3>
                <ol class="space-y-3">
                    @foreach($result->night_routine as $i => $step)
                    <li class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[var(--tx-tertiary-light)] text-[var(--tx-tertiary)] text-[10px] font-black flex items-center justify-center shrink-0 border border-white shadow-sm">{{ $i+1 }}</span>
                        <span class="text-sm font-bold text-[var(--tx-text-dark)]">{{ $step }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>

            {{-- Ingredients --}}
            <div class="glass-card border border-white/50 p-6 flex flex-col gap-5">
                <div>
                    <h3 class="font-black text-[var(--tx-quaternary)] text-sm mb-3 flex items-center gap-2">✅ Kandungan yang Cocok</h3>
                    <div class="space-y-2">
                        @foreach($result->good_ingredients as $ing)
                        <div class="bg-[var(--tx-quaternary-light)]/50 border border-white rounded-[12px] p-3">
                            <p class="font-black text-[var(--tx-quaternary)] text-xs">{{ $ing['name'] }}</p>
                            <p class="text-[10px] font-bold text-[var(--tx-text-muted)] mt-0.5">{{ $ing['benefit'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="font-black text-[var(--tx-secondary)] text-sm mb-3 flex items-center gap-2">⚠️ Hindari</h3>
                    <div class="space-y-2">
                        @foreach($result->bad_ingredients as $ing)
                        <div class="bg-red-50/60 border border-red-100 rounded-[12px] p-3">
                            <p class="font-black text-red-500 text-xs">{{ $ing['name'] }}</p>
                            <p class="text-[10px] font-bold text-[var(--tx-text-muted)] mt-0.5">{{ $ing['reason'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Recommended Products --}}
        @if($recommendedProducts->count())
        <div class="glass-card border border-white/50 overflow-hidden">
            <div class="px-8 py-5 border-b border-white/50 bg-white/20">
                <h3 class="font-black text-[var(--tx-text-dark)] text-lg">🛍️ Produk Naturea Rekomendasi</h3>
            </div>
            <div class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @foreach($recommendedProducts as $product)
                <a href="{{ route('shop.show', $product->slug) }}" class="glass-card p-4 border border-white/50 hover:-translate-y-2 transition-all group text-center">
                    <div class="aspect-square rounded-[12px] overflow-hidden mb-3 border border-white/60">
                        <img src="{{ $product->primary_image }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='https://placehold.co/120'">
                    </div>
                    <p class="font-black text-[var(--tx-text-dark)] text-xs line-clamp-2 leading-tight mb-1">{{ $product->name }}</p>
                    <p class="font-black text-[var(--tx-primary)] text-xs">{{ $product->formatted_price }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        async function deleteScan() {
            if (!confirm('Hapus hasil scan ini?')) return;
            const res = await fetch('{{ route("konsultasi.face-scan.destroy", $result) }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) window.location.href = '{{ route("konsultasi.face-scan.index") }}';
        }
    </script>
    @endpush
</x-app-layout>
