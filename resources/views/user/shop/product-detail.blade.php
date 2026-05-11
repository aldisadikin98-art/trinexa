<x-app-layout title="Trinexa - {{ $product->name }}">
    <div class="min-h-screen bg-[#FDF9F1] py-8 pb-32 md:pb-12 font-sans">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <nav class="flex text-sm text-gray-500 font-medium mb-8">
                <a href="{{ route('user.shop.index') }}" class="hover:text-[#BA7517] transition-colors">Belanja</a>
                <span class="mx-2">/</span>
                <span class="{{ $product->brand == 'Naturea' ? 'text-[#D4537E]' : 'text-emerald-600' }}">{{ $product->brand }}</span>
                <span class="mx-2">/</span>
                <span class="text-gray-800 line-clamp-1">{{ $product->name }}</span>
            </nav>

            <div class="bg-white rounded-[2rem] p-6 md:p-10 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-10">
                
                <!-- Product Image Section -->
                <div class="w-full md:w-1/2">
                    <div class="relative aspect-square rounded-3xl overflow-hidden bg-gray-50 border border-gray-100 mb-4 shadow-inner">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-8xl bg-gradient-to-br {{ $product->brand == 'Naturea' ? 'from-[#F8C8DC]/30 to-pink-100' : 'from-emerald-50 to-emerald-100' }}">
                                {{ $product->brand == 'Naturea' ? '🌸' : '🌿' }}
                            </div>
                        @endif
                        
                        @if($product->is_bundle)
                            <div class="absolute top-4 left-4 bg-gradient-to-r from-[#BA7517] to-yellow-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-lg">
                                Bundle Hemat
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Detail Section -->
                <div class="w-full md:w-1/2 flex flex-col">
                    <span class="inline-block text-xs font-bold {{ $product->brand == 'Naturea' ? 'text-[#D4537E] bg-[#F8C8DC]/30' : 'text-emerald-600 bg-emerald-50' }} px-3 py-1.5 rounded-lg uppercase tracking-widest w-max mb-4">
                        {{ $product->brand }} • {{ $product->category }}
                    </span>
                    
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-2">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-1">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                            <span class="text-sm font-bold text-gray-600 ml-1">4.9</span>
                        </div>
                        <span class="text-gray-300">|</span>
                        <span class="text-sm text-gray-500 font-medium">Terjual 1.2rb+</span>
                        <span class="text-gray-300">|</span>
                        <span class="text-sm font-bold text-emerald-500">Stok: {{ $product->stock }}</span>
                    </div>

                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 mb-8">
                        @if($product->is_bundle && $product->bundle_discount > 0)
                            <div class="flex items-center gap-3 mb-1">
                                <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded">{{ $product->bundle_discount }}% OFF</span>
                                <span class="text-gray-400 line-through text-sm font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-4xl font-extrabold text-[#BA7517]">
                                Rp {{ number_format($product->effective_price, 0, ',', '.') }}
                            </div>
                        @else
                            <div class="text-4xl font-extrabold text-[#BA7517]">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        @endif
                        
                        @if($product->reward_points > 0)
                            <div class="flex items-center gap-2 mt-4 text-sm font-bold text-yellow-600 bg-yellow-50 w-max px-3 py-1.5 rounded-lg border border-yellow-200">
                                <span>🪙</span> +{{ $product->reward_points }} Poin Harvestly
                            </div>
                        @endif
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Deskripsi Produk</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-auto flex flex-col sm:flex-row gap-4">
                        <button class="flex-1 bg-white border-2 border-[#BA7517] text-[#BA7517] font-bold py-4 px-6 rounded-2xl hover:bg-[#FDF9F1] transition-colors flex justify-center items-center gap-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            + Keranjang
                        </button>
                        <button class="flex-1 bg-gradient-to-r from-[#BA7517] to-yellow-500 text-white font-bold py-4 px-6 rounded-2xl hover:shadow-lg hover:shadow-yellow-500/30 transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2">
                            Beli Sekarang
                        </button>
                    </div>

                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Mungkin Anda Suka</h2>
                <div class="flex md:grid md:grid-cols-4 gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('user.shop.show', $related->id) }}" class="min-w-[200px] bg-white rounded-3xl p-4 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group snap-start">
                            <div class="relative w-full aspect-square rounded-2xl overflow-hidden mb-4 bg-gray-50">
                                @if($related->image_url)
                                    <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl bg-gradient-to-br {{ $related->brand == 'Naturea' ? 'from-[#F8C8DC]/30 to-pink-100' : 'from-emerald-50 to-emerald-100' }}">
                                        {{ $related->brand == 'Naturea' ? '🌸' : '🌿' }}
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-sm font-bold text-gray-800 line-clamp-2 mb-2">{{ $related->name }}</h4>
                            <p class="text-base font-extrabold text-[#BA7517]">Rp {{ number_format($related->effective_price, 0, ',', '.') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
