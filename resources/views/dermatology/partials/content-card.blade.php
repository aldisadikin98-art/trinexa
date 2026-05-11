@php
    $typeConfig = [
        'article' => ['icon' => '📄', 'label' => 'Artikel', 'color' => 'from-[var(--tx-primary)] to-[var(--tx-secondary)]', 'bg' => 'bg-[var(--tx-primary-light)]', 'text' => 'text-[var(--tx-primary)]'],
        'tip'     => ['icon' => '💡', 'label' => 'Tips',    'color' => 'from-amber-400 to-orange-400',                        'bg' => 'bg-amber-50',                 'text' => 'text-amber-600'],
        'video'   => ['icon' => '🎬', 'label' => 'Video',   'color' => 'from-red-400 to-rose-500',                            'bg' => 'bg-red-50',                   'text' => 'text-red-500'],
    ];
    $tc = $typeConfig[$content->type] ?? $typeConfig['article'];
@endphp

<a href="{{ route('dermatology.show', $content->slug) }}"
   class="group relative glass-card bg-white/50 backdrop-blur-xl rounded-[2rem] overflow-hidden border border-white/70 shadow-md hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:-translate-y-2 hover:bg-white/70 transition-all duration-300 flex flex-col h-full">

    {{-- Ambient glow behind card on hover --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] opacity-0 group-hover:opacity-40 transition-opacity duration-500 rounded-[2rem] pointer-events-none"></div>

    {{-- Image --}}
    <div class="aspect-[16/10] overflow-hidden relative rounded-t-[1.75rem]">
        <img src="{{ $content->thumbnail ? Storage::url($content->thumbnail) : 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop' }}"
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
        
        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        {{-- Type Badge --}}
        <div class="absolute top-3 left-3 bg-gradient-to-r {{ $tc['color'] }} text-white px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 shadow-md border border-white/20 backdrop-blur-md">
            <span>{{ $tc['icon'] }}</span> {{ $tc['label'] }}
        </div>
        
        {{-- Completion Badge --}}
        @if(auth()->check() && \App\Models\UserSkinProgress::where('user_id', auth()->id())->where('content_id', $content->id)->exists())
            <div class="absolute top-3 right-3 bg-green-500 text-white w-9 h-9 rounded-full flex items-center justify-center shadow-lg border-2 border-white text-sm font-black">
                ✓
            </div>
        @endif

        {{-- Video Play Button --}}
        @if($content->type === 'video')
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="w-14 h-14 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-xl border border-white">
                    <svg class="w-6 h-6 text-red-500 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Body --}}
    <div class="p-5 flex flex-col flex-1 relative z-10">
        <div class="flex items-center justify-between mb-3">
            <span class="{{ $tc['bg'] }} {{ $tc['text'] }} border border-current/20 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                {{ ucfirst($content->skin_type) }}
            </span>
            <span class="text-amber-500 font-black text-[10px] flex items-center gap-1 bg-amber-50/80 px-2.5 py-1 rounded-full border border-amber-200/50 shadow-sm backdrop-blur-sm">
                ⭐ +{{ $content->xp_reward }} XP
            </span>
        </div>
        
        <h3 class="text-base font-black text-[var(--tx-text-dark)] mb-3 group-hover:text-[var(--tx-primary)] transition-colors duration-200 line-clamp-2 leading-snug">
            {{ $content->title }}
        </h3>
        
        <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/50">
            <div class="flex items-center gap-3 text-[9px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">
                @if($content->type != 'video')
                    <span class="flex items-center gap-1.5 bg-white/60 px-2 py-1 rounded-full border border-white/60">⏱️ {{ $content->read_time ?: 5 }} mnt</span>
                @endif
                <span class="flex items-center gap-1.5 bg-white/60 px-2 py-1 rounded-full border border-white/60">👁️ {{ $content->views }}x</span>
            </div>
            <div class="w-7 h-7 rounded-full bg-gradient-to-r {{ $tc['color'] }} flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 scale-75 group-hover:scale-100 shadow-md">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>
    </div>
</a>
