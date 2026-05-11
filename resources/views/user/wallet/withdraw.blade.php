<x-app-layout>
    <x-slot name="title">Tarik Saldo | Dompet Trinexa</x-slot>

    <div class="py-12 min-h-screen relative z-10">
        
        <!-- Ambient Orbs -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-gradient-to-bl from-[var(--tx-quaternary-light)] to-[var(--tx-tertiary-light)] rounded-full blur-[100px] opacity-60 pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-80 h-80 bg-gradient-to-tr from-[var(--tx-primary-light)] to-[var(--tx-secondary-light)] rounded-full blur-[100px] opacity-50 pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Back --}}
            <a href="{{ route('user.wallet.show') }}" class="inline-flex items-center gap-2 text-sm font-black text-[var(--tx-text-muted)] hover:text-[var(--tx-primary)] mb-8 transition-all glass-card px-4 py-2 rounded-full border border-white/60 bg-white/40 shadow-sm hover:scale-105 hover:bg-white/80 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Dompet
            </a>

            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-black text-[var(--tx-text-dark)] mb-3 flex items-center md:justify-start justify-center gap-3">
                    <span class="text-5xl drop-shadow-sm">💸</span> Tarik Saldo
                </h1>
                <p class="text-sm font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Cairkan saldo Trinexa ke e-wallet atau rekening bank Anda</p>
            </div>

            @if($errors->any())
                <div class="glass-card bg-red-50/80 border border-red-200 text-red-600 font-bold text-sm px-6 py-4 rounded-2xl mb-8 shadow-sm backdrop-blur-md">
                    {{ $errors->first() }}
                </div>
            @endif
            @if(session('error'))
                <div class="glass-card bg-red-50/80 border border-red-200 text-red-600 font-bold text-sm px-6 py-4 rounded-2xl mb-8 shadow-sm backdrop-blur-md">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('user.wallet.processWithdraw') }}" method="POST"
                  x-data="{
                      selected: 0,
                      custom: '',
                      destination: '',
                      account: '',
                      balance: {{ $wallet->balance }},
                      get finalAmount() {
                          return this.custom ? parseInt(this.custom) || 0 : this.selected;
                      },
                      get balanceAfter() {
                          return Math.max(0, this.balance - this.finalAmount);
                      },
                      get isInsufficient() {
                          return this.finalAmount > this.balance;
                      },
                      get destLabel() {
                          const map = {gopay:'Nomor HP GoPay', ovo:'Nomor HP OVO', dana:'Nomor HP DANA', shopeepay:'Nomor HP ShopeePay', bank:'Nomor Rekening Bank'};
                          return map[this.destination] || 'Nomor Akun';
                      },
                      get canSubmit() {
                          return this.finalAmount >= 50000 && !this.isInsufficient && this.destination && this.account.length >= 8;
                      },
                      formatRp(n) {
                          return 'Rp ' + n.toLocaleString('id-ID');
                      },
                      selectPreset(val) {
                          this.selected = val;
                          this.custom = '';
                      }
                  }">
                @csrf
                <input type="hidden" name="amount" :value="finalAmount">
                <input type="hidden" name="destination" :value="destination">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    
                    {{-- KOLOM KIRI (Form Input) --}}
                    <div class="lg:col-span-2 space-y-10">
                        
                        {{-- STEP 1: Nominal --}}
                        <div class="glass-card bg-white/40 rounded-[2.5rem] p-8 border border-white/60 shadow-sm backdrop-blur-xl relative overflow-hidden">
                            <div class="absolute -right-20 -top-20 w-40 h-40 bg-[var(--tx-primary-light)] rounded-full blur-[60px] opacity-50 pointer-events-none"></div>

                            <div class="flex items-center gap-3 mb-6 relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center font-black text-lg shadow-inner">1</div>
                                <h2 class="text-xl font-black text-[var(--tx-text-dark)] uppercase tracking-widest">Jumlah Penarikan</h2>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-6 relative z-10">
                                @foreach([50000, 100000, 200000, 500000] as $preset)
                                    <button type="button"
                                            @click="selectPreset({{ $preset }})"
                                            :disabled="{{ $wallet->balance }} < {{ $preset }}"
                                            :class="selected === {{ $preset }} && !custom
                                                ? 'border-transparent bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white shadow-lg shadow-[var(--tx-primary)]/30 -translate-y-1'
                                                : ({{ $wallet->balance }} >= {{ $preset }}
                                                    ? 'border-white/80 bg-white/60 text-[var(--tx-text-muted)] hover:border-[var(--tx-primary)] hover:text-[var(--tx-primary)] hover:-translate-y-1 hover:shadow-md'
                                                    : 'border-white/40 bg-white/20 text-gray-400 cursor-not-allowed opacity-50')"
                                            class="border-2 rounded-2xl py-4 px-2 text-sm font-black transition-all text-center backdrop-blur-md">
                                        Rp {{ number_format($preset, 0, ',', '.') }}
                                    </button>
                                @endforeach
                            </div>

                            <div class="relative z-10">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-[var(--tx-text-muted)]">Rp</span>
                                <input type="number"
                                       x-model="custom"
                                       @input="selected = 0"
                                       placeholder="Nominal lain (min. 50.000)"
                                       min="50000"
                                       class="w-full border-2 border-white/80 focus:border-[var(--tx-primary)] focus:ring-[var(--tx-primary)] bg-white/60 rounded-2xl pl-14 pr-6 py-4 font-black text-[var(--tx-text-dark)] transition-colors shadow-inner text-lg placeholder:text-gray-400 placeholder:font-bold">
                            </div>

                            {{-- Saldo kurang warning --}}
                            <div x-show="isInsufficient && finalAmount > 0" x-cloak class="mt-4 bg-red-50/80 border border-red-200 text-red-600 text-xs font-black px-5 py-3.5 rounded-xl flex items-center gap-3 backdrop-blur-md uppercase tracking-widest relative z-10">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Saldo tidak cukup. Kurang <span class="text-red-700 bg-red-100 px-2 py-0.5 rounded ml-1" x-text="formatRp(finalAmount - balance)"></span>.</span>
                            </div>
                        </div>

                        {{-- STEP 2: Tujuan Pencairan --}}
                        <div class="glass-card bg-white/40 rounded-[2.5rem] p-8 border border-white/60 shadow-sm backdrop-blur-xl relative overflow-hidden">
                            <div class="absolute -right-20 -bottom-20 w-40 h-40 bg-[var(--tx-secondary-light)] rounded-full blur-[60px] opacity-50 pointer-events-none"></div>

                            <div class="flex items-center gap-3 mb-6 relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center font-black text-lg shadow-inner">2</div>
                                <h2 class="text-xl font-black text-[var(--tx-text-dark)] uppercase tracking-widest">Tujuan Pencairan</h2>
                            </div>

                            <div class="space-y-3 relative z-10">

                                {{-- GoPay --}}
                                <label class="cursor-pointer block group">
                                    <input type="radio" x-model="destination" @change="account=''" value="gopay" class="sr-only peer">
                                    <div class="flex items-center gap-5 p-5 rounded-2xl border-2 transition-all
                                                peer-checked:border-[#00AED6] peer-checked:bg-white peer-checked:shadow-lg
                                                border-white/60 hover:border-white bg-white/50 backdrop-blur-md">
                                        <div class="w-16 h-10 shrink-0 flex items-center justify-center bg-[#00AED6] rounded-xl px-2 shadow-sm">
                                            <svg viewBox="0 0 80 28" class="w-full h-full">
                                                <text x="4" y="21" font-family="Arial Black, sans-serif" font-size="18" font-weight="900" fill="white">Go</text>
                                                <text x="32" y="21" font-family="Arial Black, sans-serif" font-size="18" font-weight="900" fill="#000" opacity="0.85">Pay</text>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-black text-[var(--tx-text-dark)] text-sm mb-1 group-hover:text-[#00AED6] transition-colors">GoPay</p>
                                            <p class="text-[11px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Proses instan ke akun Gojek</p>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-[3px] border-white/80 peer-checked:border-[#00AED6] flex items-center justify-center shrink-0 transition-all bg-white shadow-inner" :class="destination === 'gopay' ? 'border-[#00AED6]' : 'border-gray-300'">
                                            <div class="w-3 h-3 rounded-full bg-[#00AED6] transition-all" :class="destination === 'gopay' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"></div>
                                        </div>
                                    </div>
                                </label>

                                {{-- OVO --}}
                                <label class="cursor-pointer block group">
                                    <input type="radio" x-model="destination" @change="account=''" value="ovo" class="sr-only peer">
                                    <div class="flex items-center gap-5 p-5 rounded-2xl border-2 transition-all
                                                peer-checked:border-[#4C3494] peer-checked:bg-white peer-checked:shadow-lg
                                                border-white/60 hover:border-white bg-white/50 backdrop-blur-md">
                                        <div class="w-16 h-10 shrink-0 flex items-center justify-center bg-[#4C3494] rounded-xl px-2 shadow-sm">
                                            <svg viewBox="0 0 60 28" class="w-full h-full">
                                                <text x="6" y="22" font-family="Arial Black, sans-serif" font-size="20" font-weight="900" fill="white">OVO</text>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-black text-[var(--tx-text-dark)] text-sm mb-1 group-hover:text-[#4C3494] transition-colors">OVO</p>
                                            <p class="text-[11px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Proses instan ke akun OVO</p>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-[3px] border-white/80 peer-checked:border-[#4C3494] flex items-center justify-center shrink-0 transition-all bg-white shadow-inner" :class="destination === 'ovo' ? 'border-[#4C3494]' : 'border-gray-300'">
                                            <div class="w-3 h-3 rounded-full bg-[#4C3494] transition-all" :class="destination === 'ovo' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"></div>
                                        </div>
                                    </div>
                                </label>

                                {{-- DANA --}}
                                <label class="cursor-pointer block group">
                                    <input type="radio" x-model="destination" @change="account=''" value="dana" class="sr-only peer">
                                    <div class="flex items-center gap-5 p-5 rounded-2xl border-2 transition-all
                                                peer-checked:border-[#108EE9] peer-checked:bg-white peer-checked:shadow-lg
                                                border-white/60 hover:border-white bg-white/50 backdrop-blur-md">
                                        <div class="w-16 h-10 shrink-0 flex items-center justify-center bg-[#108EE9] rounded-xl px-2 shadow-sm">
                                            <svg viewBox="0 0 70 28" class="w-full h-full">
                                                <text x="4" y="22" font-family="Arial Black, sans-serif" font-size="19" font-weight="900" fill="white">DANA</text>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-black text-[var(--tx-text-dark)] text-sm mb-1 group-hover:text-[#108EE9] transition-colors">DANA</p>
                                            <p class="text-[11px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Proses instan ke akun DANA</p>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-[3px] border-white/80 peer-checked:border-[#108EE9] flex items-center justify-center shrink-0 transition-all bg-white shadow-inner" :class="destination === 'dana' ? 'border-[#108EE9]' : 'border-gray-300'">
                                            <div class="w-3 h-3 rounded-full bg-[#108EE9] transition-all" :class="destination === 'dana' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"></div>
                                        </div>
                                    </div>
                                </label>

                                {{-- ShopeePay --}}
                                <label class="cursor-pointer block group">
                                    <input type="radio" x-model="destination" @change="account=''" value="shopeepay" class="sr-only peer">
                                    <div class="flex items-center gap-5 p-5 rounded-2xl border-2 transition-all
                                                peer-checked:border-[#EE4D2D] peer-checked:bg-white peer-checked:shadow-lg
                                                border-white/60 hover:border-white bg-white/50 backdrop-blur-md">
                                        <div class="w-16 h-10 shrink-0 flex items-center justify-center bg-[#EE4D2D] rounded-xl px-1 shadow-sm">
                                            <svg viewBox="0 0 80 28" class="w-full h-full">
                                                <text x="2" y="20" font-family="Arial Black, sans-serif" font-size="11" font-weight="900" fill="white">Shopee</text>
                                                <text x="2" y="28" font-family="Arial Black, sans-serif" font-size="11" font-weight="900" fill="#FFD700">Pay</text>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-black text-[var(--tx-text-dark)] text-sm mb-1 group-hover:text-[#EE4D2D] transition-colors">ShopeePay</p>
                                            <p class="text-[11px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">Proses instan ke ShopeePay</p>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-[3px] border-white/80 peer-checked:border-[#EE4D2D] flex items-center justify-center shrink-0 transition-all bg-white shadow-inner" :class="destination === 'shopeepay' ? 'border-[#EE4D2D]' : 'border-gray-300'">
                                            <div class="w-3 h-3 rounded-full bg-[#EE4D2D] transition-all" :class="destination === 'shopeepay' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"></div>
                                        </div>
                                    </div>
                                </label>

                                {{-- Bank Transfer --}}
                                <label class="cursor-pointer block group">
                                    <input type="radio" x-model="destination" @change="account=''" value="bank" class="sr-only peer">
                                    <div class="flex items-center gap-5 p-5 rounded-2xl border-2 transition-all
                                                peer-checked:border-[var(--tx-primary)] peer-checked:bg-white peer-checked:shadow-lg
                                                border-white/60 hover:border-white bg-white/50 backdrop-blur-md">
                                        <div class="w-16 h-10 shrink-0 flex items-center justify-center bg-gradient-to-br from-[#1E293B] to-[#0F2942] rounded-xl shadow-sm">
                                            <svg class="w-6 h-6 text-[var(--tx-secondary-light)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-black text-[var(--tx-text-dark)] text-sm mb-1 group-hover:text-[var(--tx-primary)] transition-colors">Transfer Bank</p>
                                            <p class="text-[11px] font-bold text-[var(--tx-text-muted)] uppercase tracking-widest">BCA · Mandiri · BNI · BRI · BSI</p>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-[3px] border-white/80 peer-checked:border-[var(--tx-primary)] flex items-center justify-center shrink-0 transition-all bg-white shadow-inner" :class="destination === 'bank' ? 'border-[var(--tx-primary)]' : 'border-gray-300'">
                                            <div class="w-3 h-3 rounded-full bg-[var(--tx-primary)] transition-all" :class="destination === 'bank' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"></div>
                                        </div>
                                    </div>
                                </label>

                            </div>

                            {{-- STEP 3: Input Nomor (Muncul setelah pilih tujuan) --}}
                            <div x-show="destination" x-cloak class="mt-8 pt-8 border-t border-white/60 relative z-10 animate-[slideUp_0.3s_ease-out]">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[var(--tx-primary)] to-[var(--tx-secondary)] text-white flex items-center justify-center font-black text-sm shadow-inner">3</div>
                                    <label class="block text-sm font-black text-[var(--tx-text-dark)] uppercase tracking-widest" x-text="destLabel"></label>
                                </div>
                                <input type="text" x-model="account" name="account"
                                       :placeholder="destination === 'bank' ? 'Cth: 1234567890 (tanpa spasi)' : 'Cth: 08XXXXXXXXXX'"
                                       class="w-full border-2 border-white/80 focus:border-[var(--tx-primary)] focus:ring-[var(--tx-primary)] bg-white/60 rounded-2xl px-5 py-4 font-black text-[var(--tx-text-dark)] transition-colors shadow-inner text-base placeholder:text-gray-400 placeholder:font-bold">
                                <p x-show="destination === 'bank'" class="text-[10px] text-[var(--tx-text-muted)] mt-2 font-bold uppercase tracking-widest">Pastikan nama pemilik rekening sesuai dengan nama akun Trinexa.</p>
                            </div>

                        </div>

                    </div>

                    {{-- KOLOM KANAN (Ringkasan & Sticky) --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-24 space-y-6">
                            
                            {{-- Saldo Saat Ini --}}
                            <div class="glass-card bg-gradient-to-br from-[#1E293B] to-[#0F2942] rounded-[2.5rem] p-8 border border-white/10 shadow-[0_20px_40px_rgba(15,41,66,0.3)] relative overflow-hidden group">
                                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                                
                                <p class="text-white/60 text-[10px] font-black uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[var(--tx-secondary)] animate-pulse"></span> Saldo Trinexa
                                </p>
                                <p class="text-white font-black text-3xl mb-1 tracking-tight drop-shadow-sm">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                                
                                @if($wallet->balance < 50000)
                                    <span class="inline-block mt-3 text-[10px] bg-red-500/20 border border-red-500/50 text-red-200 font-bold px-3 py-1.5 rounded-xl uppercase tracking-widest backdrop-blur-sm">Saldo min. penarikan 50rb</span>
                                @endif
                            </div>

                            {{-- Preview Saldo Setelah --}}
                            <div x-show="finalAmount >= 50000 && !isInsufficient" x-cloak 
                                 class="glass-card bg-white/60 border border-white/80 rounded-[2rem] p-6 shadow-sm backdrop-blur-xl animate-[slideUp_0.3s_ease-out]">
                                <div class="flex justify-between items-center mb-4 border-b border-white/80 pb-4">
                                    <p class="text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Jumlah Ditarik</p>
                                    <p class="font-black text-red-500 text-lg" x-text="'-' + formatRp(finalAmount)"></p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-[10px] font-black text-[var(--tx-text-dark)] uppercase tracking-widest">Sisa Saldo (Est)</p>
                                    <p class="font-black text-[var(--tx-primary)] text-2xl drop-shadow-sm" x-text="formatRp(balanceAfter)"></p>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                    :disabled="!canSubmit"
                                    :class="canSubmit ? 'bg-gradient-to-r from-[var(--tx-primary)] to-[var(--tx-secondary)] hover:-translate-y-1 shadow-lg shadow-[var(--tx-primary)]/30 text-white cursor-pointer border border-white/20' : 'bg-gray-100/50 border border-white/60 text-gray-400 cursor-not-allowed backdrop-blur-md'"
                                    class="w-full font-black py-5 rounded-[1.5rem] transition-all text-sm uppercase tracking-widest relative overflow-hidden group">
                                
                                <div x-show="canSubmit" class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                
                                <div class="relative z-10 flex items-center justify-center gap-2">
                                    <span x-show="canSubmit">Konfirmasi Tarik ✨</span>
                                    <span x-show="!canSubmit">Lengkapi Data 🔒</span>
                                </div>
                            </button>

                            <div class="glass-card bg-white/40 border border-white/60 p-4 rounded-[1.5rem] flex items-center justify-center gap-3 shadow-sm">
                                <span class="text-xl drop-shadow-sm">🔒</span>
                                <p class="text-[10px] font-bold text-[var(--tx-text-muted)] leading-tight uppercase tracking-widest">Transaksi Aman & Terenkripsi<br><span class="font-black text-[var(--tx-text-dark)]">(Mode Simulasi)</span></p>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
