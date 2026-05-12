<x-app-layout title="Trinexa - Eksplor Produk">
    <div class="min-h-screen bg-[#FAF9F7] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- SEARCH & FILTER SECTION -->
            <div class="mb-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Eksplor Produk</h1>
                    
                    <!-- Search Bar -->
                    <form action="{{ route('user.shop.index') }}" method="GET" class="w-full md:w-80 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari skincare atau botol..." class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/20 rounded-full text-sm transition-all shadow-sm outline-none">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </form>
                </div>

                <!-- Filter Buttons -->
                <div class="flex items-center gap-3 overflow-x-auto pb-4 scrollbar-hide">
                    <a href="{{ route('user.shop.index') }}" class="{{ !request('brand') ? 'bg-[#D4AF37] text-white border-[#D4AF37]' : 'bg-white text-gray-500 border-gray-200 hover:text-[#D4AF37] hover:border-[#D4AF37]' }} border px-5 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap shadow-sm">Semua</a>
                    <a href="{{ route('user.shop.index', ['brand' => 'Naturea']) }}" class="{{ request('brand') == 'Naturea' ? 'bg-[#D4AF37] text-white border-[#D4AF37]' : 'bg-white text-gray-500 border-gray-200 hover:text-[#D4AF37] hover:border-[#D4AF37]' }} border px-5 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap shadow-sm">Naturea</a>
                    <a href="{{ route('user.shop.index', ['brand' => 'Karebla']) }}" class="{{ request('brand') == 'Karebla' ? 'bg-[#D4AF37] text-white border-[#D4AF37]' : 'bg-white text-gray-500 border-gray-200 hover:text-[#D4AF37] hover:border-[#D4AF37]' }} border px-5 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap shadow-sm">Karebla</a>
                    <button class="bg-white text-gray-500 border border-gray-200 hover:text-[#D4AF37] hover:border-[#D4AF37] px-5 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap shadow-sm">Terlaris</button>
                    <button class="bg-white text-gray-500 border border-gray-200 hover:text-[#D4AF37] hover:border-[#D4AF37] px-5 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap shadow-sm">Terbaru</button>
                    <button class="bg-white text-gray-500 border border-gray-200 hover:text-[#D4AF37] hover:border-[#D4AF37] px-5 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap shadow-sm flex items-center gap-1">
                        Harga <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                    </button>
                </div>
            </div>

            <!-- PRODUCT CARDS GRID -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-[2rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group flex flex-col relative">
                            
                            <!-- Bagian Atas Kartu (Gambar) -->
                            <div class="relative w-full aspect-square overflow-hidden {{ $product->brand == 'Naturea' ? 'bg-[#F8C8DC]/20' : 'bg-[#F5E6DA]/50' }} flex items-center justify-center">
                                
                                <!-- Tombol Favorit -->
                                <button class="absolute top-4 right-4 z-20 w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-pink-500 transition-colors shadow-sm">
                                    <svg class="w-5 h-5 hover:fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </button>

                                <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-6 group-hover:scale-110 transition-transform duration-500">
                            </div>
                            
                            <!-- Bagian Bawah Kartu (Info) -->
                            <div class="p-5 flex-1 flex flex-col relative bg-white">
                                <!-- Kategori Pill -->
                                <span class="text-[10px] font-bold {{ $product->brand == 'Naturea' ? 'text-[#D4537E] bg-[#F8C8DC]/30' : 'text-[#D4AF37] bg-[#F5E6DA]' }} px-2 py-1 rounded-md uppercase tracking-widest w-max mb-3">
                                    {{ $product->brand }}
                                </span>
                                
                                <a href="{{ route('user.shop.show', $product->id) }}">
                                    <h3 class="text-base font-bold text-gray-800 leading-tight mb-2 line-clamp-2 hover:text-[#D4AF37] transition-colors">{{ $product->name }}</h3>
                                </a>
                                
                                <!-- Rating & Ulasan -->
                                <div class="flex items-center gap-1 mb-4">
                                    <svg class="w-4 h-4 text-[#D4AF37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-xs font-bold text-gray-600">4.9</span>
                                    <span class="text-xs text-gray-500 ml-1">(128 Ulasan)</span>
                                </div>

                                <div class="mt-auto">
                                    <p class="text-lg font-extrabold text-[#D4AF37]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>

                                <!-- Hover Action (Tambah ke Keranjang) -->
                                <div class="absolute bottom-5 left-5 right-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <button class="w-full bg-[#0F2942] text-white font-bold text-sm py-3 rounded-full shadow-lg hover:bg-[#1a3f63] transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-10 flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-[2rem] p-16 text-center shadow-sm border border-gray-100">
                    <div class="w-24 h-24 bg-[#F5E6DA]/50 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">🔍</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Mungkin kata kunci atau filter pencarianmu kurang tepat. Coba ubah pencarian atau lihat semua produk kami.</p>
                    <a href="{{ route('user.shop.index') }}" class="inline-block bg-[#0F2942] text-white font-bold py-3 px-8 rounded-full hover:bg-[#1a3f63] transition-colors shadow-lg">Lihat Semua Produk</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
