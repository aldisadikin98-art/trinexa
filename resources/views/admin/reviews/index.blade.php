<x-admin-layout>
    <x-slot name="title">Moderasi Ulasan</x-slot>

    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.ulasan.index', ['status' => 'pending']) }}" 
           class="px-5 py-2 rounded-xl text-sm font-bold {{ $status === 'pending' ? 'bg-[#0F2942] text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
           Menunggu Moderasi
           @if($status !== 'pending' && \App\Models\Review::where('status', 'pending')->count() > 0)
               <span class="ml-2 bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ \App\Models\Review::where('status', 'pending')->count() }}</span>
           @endif
        </a>
        <a href="{{ route('admin.ulasan.index', ['status' => 'approved']) }}" 
           class="px-5 py-2 rounded-xl text-sm font-bold {{ $status === 'approved' ? 'bg-[#0F2942] text-white' : 'bg-white text-gray-600 border border-gray-200' }}">Disetujui</a>
        <a href="{{ route('admin.ulasan.index', ['status' => 'rejected']) }}" 
           class="px-5 py-2 rounded-xl text-sm font-bold {{ $status === 'rejected' ? 'bg-[#0F2942] text-white' : 'bg-white text-gray-600 border border-gray-200' }}">Ditolak</a>
    </div>

    <div class="space-y-6">
        @forelse($reviews as $review)
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 relative">
                {{-- Badge Status --}}
                <div class="absolute top-6 right-6">
                    @if($review->status === 'pending') <span class="bg-yellow-100 text-yellow-700 text-xs font-black px-3 py-1 rounded-full">⏳ Pending</span>
                    @elseif($review->status === 'approved') <span class="bg-green-100 text-green-700 text-xs font-black px-3 py-1 rounded-full">✅ Tampil</span>
                    @else <span class="bg-red-100 text-red-700 text-xs font-black px-3 py-1 rounded-full">❌ Ditolak</span>
                    @endif
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    {{-- Kiri: Info Ulasan --}}
                    <div class="flex-1 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#F8C8DC] to-[#F09EC0] flex items-center justify-center text-pink-700 font-bold">
                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">{{ $review->user->name ?? 'User' }}</div>
                                <div class="text-xs text-gray-500">{{ $review->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-[#D4AF37] text-lg tracking-widest">{{ $review->stars }}</span>
                            @if($review->skin_type)
                                <span class="bg-[#F8C8DC] text-pink-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $review->skin_type }}</span>
                            @endif
                        </div>

                        <p class="text-gray-700 text-sm leading-relaxed bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            {{ $review->body }}
                        </p>

                        @if($review->images->count() > 0)
                            <div class="flex gap-2">
                                @foreach($review->images as $img)
                                    <a href="{{ $img->url }}" target="_blank">
                                        <img src="{{ $img->url }}" class="w-16 h-16 object-cover rounded-xl border border-gray-200">
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Balasan Admin --}}
                        @if($review->admin_reply)
                            <div class="bg-[#F5E6C8] p-4 rounded-2xl border border-[#D4AF37]/30 mt-3">
                                <div class="text-xs font-black text-[#9a7c1f] mb-1">Balasan Naturea:</div>
                                <p class="text-sm text-gray-800">{{ $review->admin_reply }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Kanan: Info Produk & Aksi --}}
                    <div class="md:w-64 shrink-0 md:border-l md:border-gray-100 md:pl-6 flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Diulas pada Produk</p>
                            <a href="{{ route('shop.show', $review->product->slug) }}" target="_blank" class="flex items-center gap-3 hover:bg-gray-50 p-2 rounded-xl transition-colors">
                                <img src="{{ $review->product->primary_image }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&q=80'">
                                <span class="font-bold text-sm text-[#0F2942] line-clamp-2">{{ $review->product->name }}</span>
                            </a>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                            @if($review->status === 'pending')
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.ulasan.approve', $review) }}" method="POST" class="flex-1">
                                        @csrf @method('PATCH')
                                        <button class="w-full bg-green-100 text-green-700 font-bold py-2 rounded-xl hover:bg-green-200 text-sm">Terima</button>
                                    </form>
                                    <form action="{{ route('admin.ulasan.reject', $review) }}" method="POST" class="flex-1">
                                        @csrf @method('PATCH')
                                        <button class="w-full bg-red-100 text-red-700 font-bold py-2 rounded-xl hover:bg-red-200 text-sm">Tolak</button>
                                    </form>
                                </div>
                            @endif

                            @if($review->status === 'approved' && !$review->admin_reply)
                                <button onclick="document.getElementById('reply-form-{{ $review->id }}').classList.toggle('hidden')" 
                                        class="w-full bg-gray-100 text-gray-700 font-bold py-2 rounded-xl hover:bg-gray-200 text-sm">
                                    Balas Ulasan
                                </button>
                            @endif

                            <form action="{{ route('admin.ulasan.destroy', $review) }}" method="POST" onsubmit="return confirm('Yakin hapus ulasan ini permanen?');">
                                @csrf @method('DELETE')
                                <button class="w-full text-red-500 font-bold py-2 hover:underline text-sm mt-2">Hapus Permanen</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Form Balasan --}}
                <form id="reply-form-{{ $review->id }}" action="{{ route('admin.ulasan.reply', $review) }}" method="POST" class="hidden mt-4 pt-4 border-t border-gray-100">
                    @csrf
                    <label class="block text-sm font-bold text-gray-700 mb-2">Balas sebagai Naturea</label>
                    <div class="flex gap-2">
                        <input type="text" name="reply" required placeholder="Terima kasih Kak atas ulasannya..." 
                               class="flex-1 border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-[#D4AF37]">
                        <button class="bg-[#0F2942] text-white px-4 py-2 rounded-xl font-bold text-sm">Kirim</button>
                    </div>
                </form>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100">
                <div class="text-6xl mb-4">⭐</div>
                <h3 class="text-lg font-bold text-gray-700">Tidak ada ulasan</h3>
                <p class="text-gray-400 text-sm mt-1">Belum ada ulasan dengan status ini.</p>
            </div>
        @endforelse

        {{ $reviews->withQueryString()->links() }}
    </div>
</x-admin-layout>
