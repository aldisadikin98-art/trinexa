<x-admin-layout title="Kelola Produk Karebla">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Katalog Produk Karebla</h2>
                <a href="{{ route('admin.karebla.produk.create') }}" class="bg-[#0F2942] hover:bg-[#15385a] text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    + Tambah Produk
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <form action="{{ route('admin.karebla.produk.index') }}" method="GET" class="flex gap-2 max-w-md">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="w-full rounded-lg border-gray-300 focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] focus:ring-opacity-50 text-sm">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700">Cari</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Koin</th>
                                <th class="px-6 py-4">Stok</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @php $images = $product->images ?? []; @endphp
                                            <img src="{{ $images[0] ?? '' }}" alt="" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $product->name }}</p>
                                                <p class="text-[10px] text-gray-500">{{ $product->collection }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-[#D4AF37]">{{ number_format($product->coin_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        @if($product->stock <= 5)
                                            <span class="text-red-500 font-bold">{{ $product->stock }} (Menipis)</span>
                                        @else
                                            <span class="text-gray-800 font-medium">{{ $product->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.karebla.produk.toggle', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2 py-1 rounded text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.karebla.produk.edit', $product->slug) }}" class="text-blue-600 hover:underline font-semibold">Edit</a>
                                            <form action="{{ route('admin.karebla.produk.destroy', $product->slug) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($products->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada produk.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-100">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
