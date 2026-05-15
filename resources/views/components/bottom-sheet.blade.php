{{-- Bottom Sheet Overlay --}}
<div id="bsOverlay" class="fixed inset-0 bg-[#0F2942]/60 backdrop-blur-[4px] z-[100] hidden opacity-0 transition-opacity duration-300" onclick="closeBottomSheet()"></div>

{{-- Bottom Sheet Panel --}}
<div id="bsPanel" class="fixed bottom-0 left-0 right-0 md:left-1/2 md:-translate-x-1/2 md:max-w-md w-full glass-card bg-white/90 backdrop-blur-xl border-t border-white/80 rounded-t-[2.5rem] z-[110] translate-y-full transition-transform duration-300 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] flex flex-col max-h-[90vh]">
    
    {{-- Handle Bar --}}
    <div class="flex justify-center pt-4 pb-2 cursor-pointer shrink-0" onclick="closeBottomSheet()">
        <div class="w-14 h-1.5 bg-gray-300/50 rounded-full"></div>
    </div>

    {{-- Close Button --}}
    <button onclick="closeBottomSheet()" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center bg-gray-100/50 text-gray-500 rounded-full hover:bg-gray-200 transition-colors shadow-sm">
        ✕
    </button>

    <div class="flex-1 overflow-y-auto px-6 pb-8 pt-2 scrollbar-hide">
        <form id="bsForm" method="POST">
            @csrf
            <input type="hidden" name="product_id" id="bsProductId">
            <input type="hidden" id="bsMode" value="cart">
            
            {{-- Header Info --}}
            <div class="flex items-end gap-5 pb-5 border-b border-gray-200/50">
                <div class="w-24 h-24 rounded-[1.25rem] bg-white/60 overflow-hidden shrink-0 border border-white shadow-sm relative -mt-6">
                    <img id="bsProductImg" src="" alt="Product" class="w-full h-full object-cover mix-blend-multiply">
                </div>
                <div class="flex-1 pb-1">
                    <div id="bsProductPrice" class="font-black text-[var(--tx-primary)] text-xl leading-none drop-shadow-sm"></div>
                    <div class="text-xs font-bold text-[var(--tx-text-muted)] mt-2">Stok: <strong id="bsProductStock" class="text-[var(--tx-text-dark)]"></strong></div>
                </div>
            </div>

            <div class="mt-5">
                <h4 id="bsProductName" class="font-black text-[var(--tx-text-dark)] text-base leading-snug line-clamp-2"></h4>
            </div>

            {{-- Qty Selector --}}
            <div class="py-5 mt-3 border-t border-gray-200/50">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-black text-[var(--tx-text-dark)] text-sm uppercase tracking-widest">Jumlah</span>
                    <div class="flex items-center gap-3 bg-white/60 border border-white rounded-xl p-1 shadow-inner">
                        <button type="button" onclick="updateBsQty(-1)" class="w-9 h-9 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-600 hover:text-[var(--tx-primary)] font-black text-lg transition-colors">−</button>
                        <input type="number" name="quantity" id="bsQtyInput" value="1" min="1" readonly class="w-12 p-0 text-center font-black text-base text-[var(--tx-text-dark)] bg-transparent border-none focus:ring-0">
                        <button type="button" onclick="updateBsQty(1)" class="w-9 h-9 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-600 hover:text-[var(--tx-primary)] font-black text-lg transition-colors">+</button>
                    </div>
                </div>
                
                {{-- Estimasi Koin --}}
                <div class="flex items-center gap-2 bg-amber-50/80 border border-amber-100 px-4 py-2.5 rounded-xl shadow-sm">
                    <span class="text-xs font-bold text-amber-700">Estimasi Koin Didapat:</span>
                    <span id="bsCoinEstimate" class="text-xs font-black text-amber-500">⭐ +0</span>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="pt-3">
                <button type="button" id="bsSubmitBtn" onclick="submitBottomSheet()" class="w-full btn-gradient font-black py-4 rounded-2xl transition-all shadow-lg hover:scale-105 flex justify-center items-center gap-2 text-xs uppercase tracking-widest">
                    <span id="bsBtnIcon"></span>
                    <span id="bsBtnText">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let bsState = {
        mode: 'cart',
        productId: null,
        name: '',
        price: 0,
        stock: 1,
        qty: 1
    };

    function openBottomSheet(mode, id, name, price, stock, img, initQty = 1) {
        bsState = { mode, productId: id, name, price, stock, qty: initQty };
        
        document.getElementById('bsProductId').value = id;
        document.getElementById('bsMode').value = mode;
        document.getElementById('bsProductName').textContent = name;
        document.getElementById('bsProductStock').textContent = stock;
        document.getElementById('bsProductImg').src = img;
        
        document.getElementById('bsQtyInput').value = initQty;
        updateBsDynamicUI();

        const overlay = document.getElementById('bsOverlay');
        const sheet = document.getElementById('bsPanel');
        
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            sheet.classList.remove('translate-y-full');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeBottomSheet() {
        const overlay = document.getElementById('bsOverlay');
        const sheet = document.getElementById('bsPanel');
        
        overlay.classList.add('opacity-0');
        sheet.classList.add('translate-y-full');
        
        setTimeout(() => {
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    function updateBsQty(change) {
        let newVal = parseInt(document.getElementById('bsQtyInput').value) + change;
        if (newVal < 1) newVal = 1;
        if (newVal > bsState.stock) newVal = bsState.stock;
        
        document.getElementById('bsQtyInput').value = newVal;
        bsState.qty = newVal;
        updateBsDynamicUI();
    }

    function updateBsDynamicUI() {
        const currentTotal = bsState.price * bsState.qty;
        document.getElementById('bsProductPrice').textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(currentTotal);
        
        const coins = Math.floor(currentTotal / 10000);
        document.getElementById('bsCoinEstimate').textContent = `⭐ +${coins}`;

        const btn = document.getElementById('bsSubmitBtn');
        const btnText = document.getElementById('bsBtnText');
        const btnIcon = document.getElementById('bsBtnIcon');

        if (bsState.mode === 'cart') {
            btn.className = "w-full font-black py-4 rounded-2xl transition-all shadow-sm border-2 border-[var(--tx-primary)] text-[var(--tx-primary)] bg-white/60 hover:bg-[var(--tx-primary-light)] flex justify-center items-center gap-2 text-xs uppercase tracking-widest";
            btnText.textContent = "Masukkan Keranjang";
            btnIcon.innerHTML = `<span>🛒</span>`;
        } else {
            btn.className = "w-full btn-gradient font-black py-4 rounded-2xl transition-all shadow-lg hover:scale-105 flex justify-center items-center gap-2 text-xs uppercase tracking-widest";
            btnText.textContent = "Beli Sekarang";
            btnIcon.innerHTML = `<span>⚡</span>`;
        }
    }

    function showBsToast(msg, type = 'success') {
        const container = document.getElementById('toast-container');
        if(!container) return;

        const toast = document.createElement('div');
        toast.className = `px-6 py-3 rounded-full shadow-lg font-black text-xs uppercase tracking-widest transform transition-all duration-300 translate-y-[-20px] opacity-0 flex items-center gap-2 border ${type === 'success' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-600 border-red-200'}`;
        toast.innerHTML = type === 'success' ? `✅ ${msg}` : `❌ ${msg}`;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-y-[-20px]', 'opacity-0');
        }, 10);

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-[-20px]');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function submitBottomSheet() {
        const form = document.getElementById('bsForm');
        const mode = document.getElementById('bsMode').value;
        const btn = document.getElementById('bsSubmitBtn');
        
        if (mode === 'buy' || mode === 'checkout') {
            form.action = "{{ route('checkout.quick') }}";
            const formData = new FormData(form);
            
            const oldHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Memproses...';

            fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData,
            })
            .then(async response => {
                const data = await response.json().catch(() => null);
                if (!response.ok || !data.success) throw new Error(data?.error || data?.message || 'Gagal memproses pesanan kilat');
                
                // Redirect on success
                window.location.href = "{{ route('checkout.index', ['mode' => 'quick']) }}";
            })
            .catch(err => {
                showBsToast(err.message, 'error');
                btn.disabled = false;
                btn.innerHTML = oldHtml;
            });

            return;
        }

        // Mode Cart (AJAX)
        form.action = "{{ route('cart.store') }}";
        const formData = new FormData(form);
        
        const oldHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Menambahkan...';

        fetch(form.action, {
            method: 'POST',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData,
        })
        .then(async response => {
            const data = await response.json().catch(() => null);
            if (!response.ok) throw new Error(data?.error || data?.message || 'Gagal menambahkan ke keranjang');
            return data;
        })
        .then(data => {
            closeBottomSheet();
            if (data && data.cart_count !== undefined) {
                const badge = document.getElementById('navCartBadge');
                if (badge) {
                    badge.textContent = data.cart_count;
                    badge.classList.remove('hidden');
                }
            }
            showBsToast(`${bsState.qty} item ditambahkan!`, 'success');
        })
        .catch(err => {
            showBsToast(err.message, 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = oldHtml;
        });
    }
</script>
