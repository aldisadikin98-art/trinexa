<x-app-layout>
    <x-slot name="title">Dermatology | Trinexa</x-slot>

    {{-- HERO SECTION --}}
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[var(--tx-pink)]/60 via-[var(--tx-cream)]/40 to-transparent pointer-events-none"></div>
        <div class="absolute right-0 top-0 w-96 h-96 bg-gradient-to-bl from-[var(--tx-secondary-light)] to-[var(--tx-tertiary-light)] rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10 flex flex-col md:flex-row items-center justify-between gap-10">
            <div class="md:w-1/2">
                <div class="inline-flex items-center gap-2 glass-card bg-white/50 border border-white/70 px-5 py-2.5 rounded-full text-[var(--tx-primary)] font-black text-[10px] uppercase tracking-widest mb-6 shadow-sm">
                    <span class="text-lg">🩺</span> Dermatology by Trinexa
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-[var(--tx-text-dark)] leading-tight mb-6">
                    Kenali kulitmu, <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)]">rawat dengan tepat.</span>
                </h1>
                <p class="text-[var(--tx-text-muted)] font-medium text-lg max-w-lg mb-10 leading-relaxed">
                    Pelajari rahasia kulit sehat dengan panduan ahli, temukan rutinitas yang cocok, dan kumpulkan poin dari setiap artikel yang kamu baca.
                </p>
                
                <div class="flex flex-wrap items-center gap-4">
                    @foreach([['📄', 'Artikel', $stats['articles']], ['💡', 'Tips', $stats['tips']], ['🎬', 'Video', $stats['videos']]] as [$icon, $label, $count])
                    <div class="glass-card bg-white/60 border border-white/80 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm hover:-translate-y-1 transition-transform">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] flex items-center justify-center text-lg shadow-inner">{{ $icon }}</div>
                        <div>
                            <p class="text-[10px] text-[var(--tx-text-muted)] font-black uppercase tracking-widest">{{ $label }}</p>
                            <p class="text-2xl font-black text-[var(--tx-text-dark)]">{{ $count }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="md:w-1/2 flex justify-center md:justify-end relative">
                <div class="absolute -inset-4 bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] rounded-[40px] blur-2xl opacity-20 animate-pulse"></div>
                <div class="glass-card border-4 border-white/80 w-full max-w-md aspect-square rounded-[3rem] overflow-hidden shadow-2xl relative">
                    <img src="https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover" alt="Skincare routine">
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent p-8 pt-20">
                        <p class="text-white/70 font-black text-[10px] uppercase tracking-widest mb-2">✨ Materi Terbaru</p>
                        <p class="text-white font-black text-xl leading-tight">Panduan Lengkap Merawat Kulit di Iklim Tropis</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="glass-card bg-white/80 backdrop-blur-xl sticky top-20 z-40 border-b border-white/60 shadow-[0_4px_20px_rgba(0,0,0,0.04)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form action="{{ route('dermatology.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 no-scrollbar">
                    @php
                        $tabs = [
                            null       => 'Semua Tipe',
                            'article'  => '📄 Artikel',
                            'tip'      => '💡 Tips',
                            'video'    => '🎬 Video',
                        ];
                    @endphp
                    @foreach($tabs as $val => $label)
                        <a href="{{ route('dermatology.index', array_filter(['type' => $val, 'skin_type' => $skinType, 'search' => $search])) }}"
                           class="shrink-0 px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all border
                                  {{ $type == $val ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white border-transparent shadow-md' : 'bg-white/60 text-[var(--tx-text-muted)] border-white/60 hover:text-[var(--tx-primary)] hover:bg-white/80 hover:border-[var(--tx-primary)]/30' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <select name="skin_type" class="text-xs bg-white/60 border border-white/80 rounded-full focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)] font-black px-5 py-2.5 uppercase tracking-widest backdrop-blur-md shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Kondisi</option>
                        @foreach(['oily' => 'Berminyak', 'dry' => 'Kering', 'combination' => 'Combination', 'sensitive' => 'Sensitive', 'normal' => 'Normal'] as $val => $lbl)
                            <option value="{{ $val }}" {{ $skinType == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>

                    <div class="relative">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari materi..."
                            class="w-full sm:w-64 text-xs bg-white/60 border border-white/80 rounded-full focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)] font-bold px-5 py-2.5 pl-11 placeholder:text-gray-400 backdrop-blur-md shadow-sm">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--tx-text-muted)]">🔍</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen relative z-10">

        @if($search || $type || $skinType)
            {{-- SEARCH RESULTS --}}
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-[var(--tx-text-dark)]">Hasil Pencarian</h2>
                    <p class="text-sm font-bold text-[var(--tx-text-muted)] mt-1 uppercase tracking-widest">Ditemukan {{ $contents->count() }} materi</p>
                </div>
                <a href="{{ route('dermatology.index') }}" class="text-[10px] font-black text-red-500 hover:text-white hover:bg-red-500 bg-red-50 border border-red-200 px-5 py-2.5 rounded-full transition-all uppercase tracking-widest">
                    Hapus Filter ✕
                </a>
            </div>

            @if($contents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($contents as $content)
                        @include('dermatology.partials.content-card', ['content' => $content])
                    @endforeach
                </div>
            @else
                <div class="text-center py-24 glass-card bg-white/40 rounded-[3rem] border border-white/60">
                    <div class="text-6xl mb-6 opacity-50">🔍</div>
                    <h3 class="text-2xl font-black text-[var(--tx-text-dark)] mb-2">Materi tidak ditemukan</h3>
                    <p class="text-[var(--tx-text-muted)] font-bold text-sm uppercase tracking-widest">Coba gunakan kata kunci lain atau ubah filter.</p>
                </div>
            @endif

        @else
            {{-- NORMAL MODE --}}

            {{-- ✨ PILIHAN EDITOR --}}
            @if($featuredContents->count() > 0)
            <div class="mb-16">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-400 flex items-center justify-center text-white shadow-lg shadow-amber-200 text-xl">✨</div>
                    <h2 class="text-3xl font-black text-[var(--tx-text-dark)]">Pilihan Editor</h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($featuredContents as $content)
                        <a href="{{ route('dermatology.show', $content->slug) }}" class="group relative glass-card bg-white/50 rounded-[2.5rem] overflow-hidden border border-white/70 shadow-md hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] hover:-translate-y-2 transition-all duration-300 flex flex-col sm:flex-row">
                            <div class="sm:w-2/5 aspect-[4/3] sm:aspect-auto overflow-hidden relative">
                                <img src="{{ $content->thumbnail ? Storage::url($content->thumbnail) : 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop' }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/10 group-hover:to-black/20 transition-all duration-300"></div>
                                <div class="absolute top-4 left-4 bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-white/20 shadow-md">
                                    {{ $content->type == 'article' ? '📄 Artikel' : ($content->type == 'tip' ? '💡 Tips' : '🎬 Video') }}
                                </div>
                            </div>
                            <div class="sm:w-3/5 p-8 flex flex-col justify-center bg-white/30 backdrop-blur-md">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="bg-[var(--tx-primary-light)] text-[var(--tx-primary)] px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-[var(--tx-primary)]/20 shadow-sm">
                                        {{ ucfirst($content->skin_type) }} Skin
                                    </span>
                                    <span class="text-amber-500 font-black text-[10px] flex items-center gap-1 bg-amber-50 px-3 py-1 rounded-full border border-amber-200 shadow-sm">
                                        ⭐ +{{ $content->xp_reward }} XP
                                    </span>
                                </div>
                                <h3 class="text-xl font-black text-[var(--tx-text-dark)] mb-3 group-hover:text-[var(--tx-primary)] transition-colors line-clamp-2 leading-tight">
                                    {{ $content->title }}
                                </h3>
                                <p class="text-sm font-medium text-[var(--tx-text-muted)] line-clamp-2 mb-6 leading-relaxed">
                                    @if($content->type == 'video') Video edukasi interaktif tentang perawatan kulit.
                                    @else {{ Str::limit(strip_tags($content->content), 120) }}
                                    @endif
                                </p>
                                <div class="flex items-center gap-4 text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">
                                    <span class="flex items-center gap-1.5 bg-white/60 px-3 py-1.5 rounded-full border border-white/60">⏱️ {{ $content->read_time ?: 5 }} mnt</span>
                                    <span class="flex items-center gap-1.5 bg-white/60 px-3 py-1.5 rounded-full border border-white/60">👁️ {{ $content->views }}x</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 📄 ARTIKEL TERBARU --}}
            @if($latestArticles->count() > 0)
            <div class="mb-16">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] flex items-center justify-center text-white text-xl shadow-lg shadow-[var(--tx-primary)]/20">📄</div>
                        <h2 class="text-3xl font-black text-[var(--tx-text-dark)]">Artikel Terbaru</h2>
                    </div>
                    <a href="{{ route('dermatology.index', ['type' => 'article']) }}" class="text-[10px] font-black text-[var(--tx-primary)] bg-[var(--tx-primary-light)] px-5 py-2.5 rounded-full border border-[var(--tx-primary)]/20 hover:bg-[var(--tx-primary)] hover:text-white transition-all uppercase tracking-widest shadow-sm">
                        Lihat Semua →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($latestArticles as $content)
                        @include('dermatology.partials.content-card', ['content' => $content])
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 💡 TIPS SINGKAT --}}
            @if($quickTips->count() > 0)
            <div class="mb-16">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-400 flex items-center justify-center text-white text-xl shadow-lg shadow-amber-200">💡</div>
                        <h2 class="text-3xl font-black text-[var(--tx-text-dark)]">Tips Singkat</h2>
                    </div>
                    <a href="{{ route('dermatology.index', ['type' => 'tip']) }}" class="text-[10px] font-black text-amber-600 bg-amber-50 px-5 py-2.5 rounded-full border border-amber-200 hover:bg-amber-400 hover:text-white transition-all uppercase tracking-widest shadow-sm">
                        Lihat Semua →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($quickTips as $content)
                        @include('dermatology.partials.content-card', ['content' => $content])
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 🎬 VIDEO EDUKASI --}}
            @if($educationVideos->count() > 0)
            <div class="mb-10">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-400 to-rose-500 flex items-center justify-center text-white text-xl shadow-lg shadow-red-200">🎬</div>
                        <h2 class="text-3xl font-black text-[var(--tx-text-dark)]">Video Edukasi</h2>
                    </div>
                    <a href="{{ route('dermatology.index', ['type' => 'video']) }}" class="text-[10px] font-black text-red-500 bg-red-50 px-5 py-2.5 rounded-full border border-red-200 hover:bg-red-500 hover:text-white transition-all uppercase tracking-widest shadow-sm">
                        Lihat Semua →
                    </a>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach($educationVideos as $content)
                        @include('dermatology.partials.content-card', ['content' => $content])
                    @endforeach
                </div>
            </div>
            @endif

        @endif
    </div>
</x-app-layout>
