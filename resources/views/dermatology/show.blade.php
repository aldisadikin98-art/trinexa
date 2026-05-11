<x-app-layout>
    <x-slot name="title">{{ $content->title }} | Dermatology</x-slot>

    <!-- Header / Nav Back -->
    <div class="bg-white border-b border-gray-100 sticky top-0 z-40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="{{ route('dermatology.index') }}" class="flex items-center gap-2 text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] font-bold text-sm transition-colors group">
                <span class="w-8 h-8 rounded-full bg-gray-50 group-hover:bg-[var(--tx-primary-light)] flex items-center justify-center transition-colors">←</span>
                Kembali ke Dermatology
            </a>
            
            <button @click="toggleBookmark" x-data="bookmarkManager({{ $content->id }}, {{ $isBookmarked ? 'true' : 'false' }})" class="flex items-center gap-2 px-4 py-2 rounded-full border transition-all" :class="bookmarked ? 'border-[var(--tx-primary)] bg-[var(--tx-primary-light)] text-[var(--tx-primary)]' : 'border-gray-200 hover:border-[var(--tx-primary)] hover:text-[var(--tx-primary)] text-gray-500'">
                <svg class="w-4 h-4" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                <span class="text-xs font-black uppercase tracking-widest" x-text="bookmarked ? 'Tersimpan' : 'Simpan'"></span>
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 md:py-12 pb-32">
        
        <!-- Tags & Meta -->
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                {{ $content->type == 'article' ? '📄 Artikel' : ($content->type == 'tip' ? '💡 Tips' : '🎥 Video') }}
            </div>
            <div class="bg-[var(--tx-cream)] text-[var(--tx-primary)] px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-[var(--tx-primary)]/20">
                Untuk: {{ ucfirst($content->skin_type) }} Skin
            </div>
            <div class="text-amber-500 font-bold text-xs flex items-center gap-1 bg-amber-50 px-3 py-1 rounded-full border border-amber-100">
                ⭐ +{{ $content->xp_reward }} XP
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl md:text-4xl font-extrabold text-[var(--tx-text-dark)] leading-tight mb-6">
            {{ $content->title }}
        </h1>

        <!-- Info Bar -->
        <div class="flex flex-wrap items-center gap-6 text-sm font-bold text-gray-400 mb-10 pb-6 border-b border-gray-100 uppercase tracking-widest">
            <span class="flex items-center gap-2">📅 {{ $content->created_at->translatedFormat('d F Y') }}</span>
            <span class="flex items-center gap-2">👁️ {{ $content->views }} x dibaca</span>
            @if($content->type != 'video')
                <span class="flex items-center gap-2">⏱️ ±{{ $content->read_time ?: 5 }} menit baca</span>
            @endif
        </div>

        <!-- Media Content (Video or Image) -->
        <div class="mb-10 rounded-3xl overflow-hidden shadow-xl border border-gray-100 bg-gray-50">
            @if($content->type == 'video' && $content->video_url)
                <div class="aspect-video relative">
                    <iframe class="absolute inset-0 w-full h-full" 
                        src="{{ str_replace('watch?v=', 'embed/', $content->video_url) }}" 
                        title="YouTube video player" frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                    </iframe>
                </div>
            @else
                <img src="{{ $content->thumbnail ? Storage::url($content->thumbnail) : 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=1200&auto=format&fit=crop' }}" 
                     class="w-full h-auto max-h-[500px] object-cover" alt="{{ $content->title }}">
            @endif
        </div>

        <!-- Text Content -->
        @if($content->content)
            <div class="prose prose-lg max-w-none prose-headings:font-black prose-headings:text-[var(--tx-text-dark)] prose-p:text-gray-600 prose-p:leading-relaxed prose-a:text-[var(--tx-primary)] prose-img:rounded-3xl">
                {!! nl2br(e($content->content)) !!}
            </div>
        @endif

    </div>

    <!-- ── BOTTOM FLOATING ACTION ──────────────────────────────────────── -->
    <div class="fixed bottom-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md border-t border-gray-100 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] py-4"
         x-data="completionManager({{ $content->id }}, {{ $isCompleted ? 'true' : 'false' }}, {{ $content->xp_reward }})">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl transition-all"
                     :class="completed ? 'bg-green-100 text-green-500 shadow-inner' : 'bg-amber-100 text-amber-500 animate-pulse'">
                    <span x-show="!completed">⭐</span>
                    <span x-show="completed">🎉</span>
                </div>
                <div>
                    <p class="font-black text-[var(--tx-text-dark)]" x-text="completed ? 'XP Telah Diklaim!' : 'Dapatkan +{{ $content->xp_reward }} XP'"></p>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5" x-text="completed ? 'Materi ini sudah kamu pelajari' : 'Baca sampai selesai & klaim XP-mu'"></p>
                </div>
            </div>

            <button @click="markComplete" 
                    :disabled="completed || loading"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-full font-black text-sm uppercase tracking-widest transition-all"
                    :class="completed ? 'bg-gray-100 text-green-600 border-2 border-green-200 cursor-not-allowed' : 'btn-gradient shadow-xl hover:scale-105'">
                <span x-show="loading">Memproses...</span>
                <span x-show="!loading && !completed">✅ Tandai Selesai</span>
                <span x-show="!loading && completed">✅ Sudah Selesai</span>
            </button>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('completionManager', (contentId, initialStatus, xpReward) => ({
                completed: initialStatus,
                loading: false,
                
                async markComplete() {
                    if (this.completed) return;
                    this.loading = true;
                    
                    try {
                        const res = await fetch(`/dermatology/${contentId}/complete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await res.json();
                        
                        if (data.success) {
                            this.completed = true;
                            // Optionally trigger a confetti animation here
                            alert(`Selamat! Kamu mendapatkan ${data.xp_earned} XP.`);
                        }
                    } catch (e) {
                        alert('Gagal menandai selesai. Coba lagi.');
                    } finally {
                        this.loading = false;
                    }
                }
            }));

            Alpine.data('bookmarkManager', (contentId, initialStatus) => ({
                bookmarked: initialStatus,
                
                async toggleBookmark() {
                    try {
                        const res = await fetch(`/dermatology/${contentId}/bookmark`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await res.json();
                        
                        if (data.success) {
                            this.bookmarked = data.is_bookmarked;
                        }
                    } catch (e) {
                        alert('Gagal menyimpan bookmark.');
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
