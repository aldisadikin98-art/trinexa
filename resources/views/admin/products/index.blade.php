<x-admin-layout>
    <x-slot name="title">Kelola Produk Naturea</x-slot>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <form method="GET" action="{{ route('admin.produk.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..."
                   class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-[#D4AF37]">
            <select name="category" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-[#D4AF37]">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-200">Filter</button>
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('admin.produk.index') }}" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-red-500">Reset</a>
            @endif
        </form>
        
        <a href="{{ route('admin.produk.create') }}" class="bg-[#0F2942] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1a3d5c] transition-colors flex items-center gap-2">
            + Tambah Produk
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->primary_image }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&q=80'">
                                    <div>
                                        <p class="font-bold text-[#0F2942] line-clamp-1 max-w-[200px]">{{ $product->name }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">⭐ {{ $product->average_rating }} ({{ $product->approved_reviews_count ?? 0 }} ulasan)</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-[#F5E6C8] text-[#9a7c1f] text-xs font-bold px-2.5 py-1 rounded-full">{{ $product->category }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $product->formatted_price }}</td>
                            <td class="px-6 py-4">
                                @if($product->stock <= 0)
                                    <span class="text-red-500 font-bold">Habis</span>
                                @elseif($product->stock <= 5)
                                    <span class="text-orange-500 font-bold">{{ $product->stock }} (Menipis)</span>
                                @else
                                    <span class="text-gray-700 font-medium">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.produk.toggle', $product) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="text-gray-400 hover:text-[#0F2942]" title="Lihat di Web">👁️</a>
                                <a href="{{ route('admin.produk.edit', $product) }}" class="text-blue-500 hover:text-blue-700 font-bold px-2 py-1">Edit</a>
                                <form action="{{ route('admin.produk.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus produk ini secara permanen?');">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 font-bold px-2 py-1">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400 font-medium">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>
