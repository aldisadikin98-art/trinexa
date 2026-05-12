<x-admin-layout>
    <x-slot name="title">Manajemen Pengeluaran</x-slot>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-[var(--tx-text-dark)] mb-1">Pengeluaran Admin 💸</h2>
            <p class="text-[var(--tx-text-muted)] font-bold text-sm">Catat dan kelola pengeluaran operasional Trinexa</p>
        </div>
        
        <button onclick="openModal('addExpenseModal')" class="btn-gradient px-8 py-4 rounded-2xl flex items-center gap-3 shadow-lg shadow-pink-500/20 group">
            <span class="text-xl group-hover:rotate-90 transition-transform">➕</span>
            <span class="text-xs uppercase tracking-widest font-black">Tambah Pengeluaran</span>
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold text-sm flex items-center gap-3">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    {{-- Table Section --}}
    <div class="glass-card rounded-[2.5rem] border border-white/80 bg-white/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/60 border-b border-gray-100">
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Tanggal</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Kategori</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest">Keterangan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest text-right">Nominal</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest text-center">Bukti</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest text-center">Admin</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-white/40 transition-colors">
                            <td class="px-8 py-6">
                                <span class="text-sm font-black text-[var(--tx-text-dark)]">{{ $expense->date->format('d M Y') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                    @if($expense->category == 'stok') bg-blue-100 text-blue-700
                                    @elseif($expense->category == 'operasional') bg-pink-100 text-pink-700
                                    @elseif($expense->category == 'gaji') bg-purple-100 text-purple-700
                                    @elseif($expense->category == 'marketing') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-600 @endif
                                ">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-bold text-[var(--tx-text-muted)] line-clamp-1">{{ $expense->description }}</p>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-sm font-black text-[var(--tx-primary)]">{{ $expense->formatted_amount }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($expense->receipt_path)
                                    <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white/60 border border-gray-100 flex items-center justify-center text-lg hover:scale-110 transition-transform shadow-sm mx-auto">🖼️</a>
                                @else
                                    <span class="text-xs text-gray-300 font-bold italic">No File</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-black text-[var(--tx-text-dark)]">{{ $expense->admin->name }}</span>
                                    <span class="text-[9px] font-bold text-[var(--tx-text-muted)]">{{ $expense->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editExpense({{ json_encode($expense) }})" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.financial.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-5xl mb-4 opacity-20">💸</span>
                                    <p class="text-[var(--tx-text-muted)] font-bold">Belum ada catatan pengeluaran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="px-8 py-6 border-t border-gray-50">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    {{-- Add Expense Modal --}}
    <div id="addExpenseModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-2 border-white/60">
                <div class="bg-white p-8 sm:p-10">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-black text-[var(--tx-text-dark)]" id="modalTitle">Tambah Pengeluaran</h3>
                        <button onclick="closeModal('addExpenseModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form id="expenseForm" action="{{ route('admin.financial.expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div id="methodField"></div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                                <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full px-5 py-3 rounded-2xl border-2 border-gray-50 bg-gray-50/50 focus:border-[var(--tx-primary)] focus:bg-white transition-all font-bold text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 ml-1">Kategori</label>
                                <select name="category" required class="w-full px-5 py-3 rounded-2xl border-2 border-gray-50 bg-gray-50/50 focus:border-[var(--tx-primary)] focus:bg-white transition-all font-bold text-sm">
                                    <option value="stok">Pembelian Stok</option>
                                    <option value="operasional">Operasional</option>
                                    <option value="gaji">Gaji Karyawan</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="lain-lain">Lain-lain</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 ml-1">Keterangan</label>
                            <input type="text" name="description" required placeholder="Contoh: Bayar listrik bulanan" class="w-full px-5 py-3 rounded-2xl border-2 border-gray-50 bg-gray-50/50 focus:border-[var(--tx-primary)] focus:bg-white transition-all font-bold text-sm">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 ml-1">Nominal (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-black text-sm">Rp</span>
                                <input type="number" name="amount" required placeholder="0" class="w-full pl-12 pr-5 py-3 rounded-2xl border-2 border-gray-50 bg-gray-50/50 focus:border-[var(--tx-primary)] focus:bg-white transition-all font-bold text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-[var(--tx-text-muted)] uppercase tracking-widest mb-2 ml-1">Bukti / Nota (Opsional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-100 border-dashed rounded-2xl bg-gray-50/30 hover:bg-white transition-all group cursor-pointer relative">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-[var(--tx-primary)] transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Klik untuk upload foto</p>
                                </div>
                                <input type="file" name="receipt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" onclick="closeModal('addExpenseModal')" class="flex-1 px-6 py-4 rounded-2xl bg-gray-50 text-gray-500 font-black text-xs uppercase tracking-widest hover:bg-gray-100 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 btn-gradient px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-pink-500/20">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            // Reset form for fresh add
            if(id === 'addExpenseModal') {
                document.getElementById('modalTitle').innerText = 'Tambah Pengeluaran';
                document.getElementById('expenseForm').action = "{{ route('admin.financial.expenses.store') }}";
                document.getElementById('methodField').innerHTML = '';
                document.getElementById('expenseForm').reset();
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function editExpense(expense) {
            openModal('addExpenseModal');
            document.getElementById('modalTitle').innerText = 'Edit Pengeluaran';
            document.getElementById('expenseForm').action = "/admin/keuangan/pengeluaran/" + expense.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
            
            // Fill data
            const form = document.getElementById('expenseForm');
            form.date.value = expense.date.split('T')[0];
            form.category.value = expense.category;
            form.description.value = expense.description;
            form.amount.value = expense.amount;
        }
    </script>
    @endpush
</x-admin-layout>
