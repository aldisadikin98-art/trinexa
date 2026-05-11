<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Target Menabung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-xl text-sm mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <h3 class="text-2xl font-bold text-gray-800">Target Anda</h3>
                <a href="{{ route('user.saving-goals.create') }}" class="bg-soft-pink text-white px-5 py-2.5 rounded-full font-bold shadow-md hover:bg-pink-400 hover:-translate-y-0.5 transition transform flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Target Baru
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($goals as $goal)
                <div class="bg-white rounded-[20px] p-6 shadow-sm border border-gray-100 relative overflow-hidden hover:shadow-md transition">
                    @if($goal->is_completed)
                        <div class="absolute top-0 right-0 bg-gold text-white text-xs font-bold px-4 py-1.5 rounded-bl-xl shadow-sm">
                            Tercapai 🎉
                        </div>
                    @endif
                    
                    <h4 class="font-bold text-gray-800 text-lg pr-16">{{ $goal->title }}</h4>
                    <p class="text-sm text-gray-500 mb-6 mt-1 line-clamp-2 h-10">{{ $goal->description ?? 'Target pembulatan otomatis dari sisa belanja.' }}</p>
                    
                    <div class="bg-gray-50 p-4 rounded-xl mb-4">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Terkumpul</p>
                                <p class="text-lg font-bold text-gold">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium">Target</p>
                                <p class="text-base font-bold text-gray-800">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="w-full bg-pastel-beige rounded-full h-3 mb-2 overflow-hidden shadow-inner">
                            <div class="bg-soft-pink h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $goal->progress_percent }}%"></div>
                        </div>
                        <p class="text-right text-xs font-bold text-soft-pink">{{ number_format($goal->progress_percent, 1) }}%</p>
                    </div>

                    @if($goal->deadline)
                    <p class="text-xs text-gray-500 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Tenggat: <span class="font-semibold ml-1 {{ $goal->deadline < now() && !$goal->is_completed ? 'text-red-500' : '' }}">{{ $goal->deadline->format('d M Y') }}</span>
                    </p>
                    @endif
                </div>
                @empty
                <div class="col-span-full bg-white rounded-[20px] p-12 text-center border border-dashed border-gray-300">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-5xl">🎯</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Target</h3>
                    <p class="text-gray-500 mb-6">Mulai menabung perlahan untuk membeli produk incaran Anda!</p>
                    <a href="{{ route('user.saving-goals.create') }}" class="text-soft-pink font-bold border-2 border-soft-pink px-6 py-3 rounded-full inline-block hover:bg-pink-50 transition">Buat Target Pertama</a>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
