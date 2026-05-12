<x-app-layout>
    <x-slot name="title">Notifikasi | Trinexa</x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center text-2xl shadow-lg border border-white/50">🔔</div>
            <div>
                <h1 class="text-3xl font-black text-[var(--tx-text-dark)] leading-tight">Pemberitahuan</h1>
                <p class="text-[var(--tx-text-muted)] font-bold text-sm italic">Jangan lewatkan update terbaru dari Trinexa ✨</p>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notif)
                <div class="glass-card p-6 border border-white/60 hover:shadow-xl transition-all duration-300 relative group {{ !$notif->read_at && $notif->user_id ? 'bg-white/80' : 'bg-white/40' }}">
                    @if(!$notif->read_at && $notif->user_id)
                        <div class="absolute top-6 right-6 w-3 h-3 bg-[var(--tx-primary)] rounded-full animate-pulse shadow-[0_0_10px_var(--tx-primary)]"></div>
                    @endif
                    
                    <div class="flex gap-5">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0 shadow-inner border border-white/50
                            @if($notif->type == 'info') bg-blue-100 text-blue-600
                            @elseif($notif->type == 'success') bg-green-100 text-green-600
                            @elseif($notif->type == 'warning') bg-yellow-100 text-yellow-600
                            @else bg-red-100 text-red-600 @endif">
                            @if($notif->type == 'info') ℹ️
                            @elseif($notif->type == 'success') ✅
                            @elseif($notif->type == 'warning') ⚠️
                            @else 🚨 @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-1">{{ $notif->created_at->diffForHumans() }}</p>
                            <h3 class="font-black text-[var(--tx-text-dark)] text-lg mb-2 group-hover:text-[var(--tx-primary)] transition-colors">{{ $notif->title }}</h3>
                            <p class="text-[var(--tx-text-dark)]/80 text-sm leading-relaxed mb-4 font-medium">{{ $notif->message }}</p>
                            
                            <form action="{{ route('notifications.read', $notif) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[var(--tx-primary)] hover:text-[var(--tx-secondary)] transition-colors">
                                    {{ $notif->link ? 'Lihat Selengkapnya' : 'Tandai Selesai' }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-card text-center py-20 border border-white/50 rounded-[3rem]">
                    <div class="w-24 h-24 bg-white/50 rounded-full flex items-center justify-center text-5xl mx-auto mb-6 shadow-inner border border-white">📭</div>
                    <h3 class="text-xl font-black text-[var(--tx-text-dark)] mb-2">Belum ada notifikasi</h3>
                    <p class="text-[var(--tx-text-muted)] font-bold mb-4">Kami akan memberitahumu jika ada info menarik!</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
