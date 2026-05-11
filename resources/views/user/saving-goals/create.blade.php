<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Target Menabung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[20px] p-8">

                @if($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg text-sm mb-6">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.saving-goals.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-base font-bold text-gray-700 mb-2">Judul Target <span class="text-red-500">*</span></label>
                            <input type="text" name="title" placeholder="Cth: Skincare Glow Up Naturea" class="w-full rounded-xl border-gray-200 focus:border-soft-pink focus:ring-soft-pink p-3" required>
                        </div>

                        <div>
                            <label class="block text-base font-bold text-gray-700 mb-2">Target Nominal (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-500">Rp</span>
                                <input type="number" name="target_amount" placeholder="500000" class="w-full rounded-xl border-gray-200 focus:border-soft-pink focus:ring-soft-pink pl-12 p-3 font-bold" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-base font-bold text-gray-700 mb-2">Tenggat Waktu (Opsional)</label>
                            <input type="date" name="deadline" class="w-full rounded-xl border-gray-200 focus:border-soft-pink focus:ring-soft-pink p-3 text-gray-700">
                        </div>

                        <div class="md:col-span-2 bg-gray-50 p-6 rounded-xl border border-gray-100">
                            <label class="block text-base font-bold text-gray-700 mb-2">Produk Incaran (Opsional)</label>
                            <p class="text-sm text-gray-500 mb-4">Pilih produk jika Anda menabung khusus untuk membelinya nanti. Harga akan otomatis terisi jika Anda memilih produk.</p>
                            
                            <select name="product_id" id="product_select" class="w-full rounded-xl border-gray-200 focus:border-soft-pink focus:ring-soft-pink p-3 bg-white">
                                <option value="" data-price="">Pilih Produk di Katalog...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ (int)$product->effective_price }}">
                                        {{ $product->name }} - Rp {{ number_format($product->effective_price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end gap-4">
                        <a href="{{ route('user.saving-goals.index') }}" class="px-6 py-3 font-bold text-gray-500 hover:text-gray-800 transition">Batal</a>
                        <button type="submit" class="bg-soft-pink text-white font-bold px-8 py-3 rounded-xl shadow-md hover:bg-pink-400 transition transform hover:-translate-y-0.5">
                            Simpan Target
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('product_select').addEventListener('change', function() {
            const price = this.options[this.selectedIndex].getAttribute('data-price');
            if (price) {
                document.querySelector('input[name="target_amount"]').value = price;
            }
        });
    </script>
    @endpush
</x-app-layout>
