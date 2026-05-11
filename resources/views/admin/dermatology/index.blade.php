<x-app-layout>
    <x-slot name="title">Kelola Dermatology | Admin Trinexa</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-[var(--tx-text-dark)]">Kelola Dermatology</h1>
                <p class="text-sm font-bold text-[var(--tx-text-muted)] mt-1">Manajemen konten edukasi kulit (Artikel, Tips, Video)</p>
            </div>
            <a href="{{ route('admin.dermatology.create') }}" class="btn-gradient px-6 py-2.5 text-sm">
                + Tambah Konten
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50/80 border border-green-200 text-green-600 px-4 py-3 rounded-2xl font-bold text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4">
            <form action="{{ route('admin.dermatology.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-3">
                <select name="type" class="bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)]">
                    <option value="">Semua Tipe Konten</option>
                    <option value="article" {{ request('type') == 'article' ? 'selected' : '' }}>📄 Artikel</option>
                    <option value="tip" {{ request('type') == 'tip' ? 'selected' : '' }}>💡 Tips</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>🎥 Video</option>
                </select>

                <select name="status" class="bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)]">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>✅ Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                </select>

                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold pl-10 focus:ring-[var(--tx-primary)] text-[var(--tx-text-dark)]">
                    <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                </div>

                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-bold text-sm transition-colors">
                    Filter
                </button>
                @if(request('type') || request('status') || request('search'))
                    <a href="{{ route('admin.dermatology.index') }}" class="bg-red-50 hover:bg-red-100 text-red-500 px-4 py-2.5 rounded-xl font-bold text-sm flex items-center justify-center transition-colors">
                        ✕
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase tracking-widest text-gray-400 font-black">
                            <th class="px-6 py-4">Konten</th>
                            <th class="px-6 py-4">Kategori & Kulit</th>
                            <th class="px-6 py-4">Statistik</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm" x-data="contentManager()">
                        @forelse($contents as $content)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                                            @if($content->thumbnail)
                                                <img src="{{ Storage::url($content->thumbnail) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xl">
                                                    {{ $content->type == 'article' ? '📄' : ($content->type == 'tip' ? '💡' : '🎥') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-black text-[var(--tx-text-dark)] line-clamp-1 max-w-[250px]">{{ $content->title }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded bg-gray-100 text-gray-500">
                                                    {{ ucfirst($content->type) }}
                                                </span>
                                                <span class="text-[10px] font-bold text-amber-500">⭐ +{{ $content->xp_reward }} XP</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-[var(--tx-text-dark)] text-xs mb-1">
                                        <span class="text-gray-400 font-medium">Kondisi:</span> {{ ucfirst($content->skin_type) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                        <span>👁️ {{ $content->views }} Views</span>
                                        <span>⏳ {{ $content->read_time ?: 5 }} Mnt</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <button @click="toggleStatus('{{ $content->id }}', 'published')" 
                                            class="w-full px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest transition-colors border"
                                            :class="isPublished['{{ $content->id }}'] ?? {{ $content->is_published ? 'true' : 'false' }} ? 'bg-green-50 text-green-600 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'">
                                            <span x-text="(isPublished['{{ $content->id }}'] ?? {{ $content->is_published ? 'true' : 'false' }}) ? '✅ Published' : '📝 Draft'"></span>
                                        </button>
                                        
                                        <button @click="toggleStatus('{{ $content->id }}', 'featured')" 
                                            class="w-full px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest transition-colors border"
                                            :class="isFeatured['{{ $content->id }}'] ?? {{ $content->is_featured ? 'true' : 'false' }} ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-gray-50 text-gray-500 border-gray-200'">
                                            <span x-text="(isFeatured['{{ $content->id }}'] ?? {{ $content->is_featured ? 'true' : 'false' }}) ? '✨ Featured' : 'Biasa'"></span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.dermatology.edit', $content) }}" class="p-2 bg-gray-50 hover:bg-amber-50 text-gray-400 hover:text-amber-500 rounded-xl transition-colors">
                                            ✏️
                                        </a>
                                        <form action="{{ route('admin.dermatology.destroy', $content) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus konten ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-xl transition-colors">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold">
                                    Belum ada konten yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($contents->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $contents->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('contentManager', () => ({
                isPublished: {},
                isFeatured: {},
                
                async toggleStatus(id, type) {
                    const endpoint = type === 'published' ? `/admin/dermatology/${id}/toggle-published` : `/admin/dermatology/${id}/toggle-featured`;
                    try {
                        const res = await fetch(endpoint, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            if (type === 'published') {
                                this.isPublished[id] = !(this.isPublished[id] ?? document.querySelector(`button[x-on\\:click="toggleStatus('${id}', 'published')"] span`).innerText.includes('Published'));
                            } else {
                                this.isFeatured[id] = !(this.isFeatured[id] ?? document.querySelector(`button[x-on\\:click="toggleStatus('${id}', 'featured')"] span`).innerText.includes('Featured'));
                            }
                        }
                    } catch (e) {
                        alert('Gagal mengubah status');
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
