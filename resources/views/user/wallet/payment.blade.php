<x-app-layout title="Trinexa - Konfirmasi Pembayaran">
    <div class="min-h-screen bg-[#FDF9F1] py-8 pb-32 md:pb-12 font-sans">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[20px] p-8 text-center">
                
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-5xl">💳</span>
                </div>
                
                <h2 class="text-2xl font-bold mb-2">Selesaikan Pembayaran</h2>
                <p class="text-gray-500 mb-8">Mohon selesaikan pembayaran untuk memproses Top Up Saldo Harvestly Anda sejumlah <br><strong class="text-lg text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong></p>
                
                <button id="pay-button" class="w-full bg-soft-pink text-white font-bold py-4 rounded-xl shadow-md hover:bg-pink-400 transition transform hover:-translate-y-0.5 text-lg">
                    Bayar Sekarang
                </button>
                
                <a href="{{ route('user.wallet.topup') }}" class="block mt-6 text-sm text-gray-500 hover:text-gray-800 font-semibold">
                    Batalkan Top Up
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
          onSuccess: function(result){
            window.location.href = "{{ route('user.wallet.show') }}";
          },
          onPending: function(result){
            window.location.href = "{{ route('user.wallet.history') }}";
          },
          onError: function(result){
            alert("Payment failed!");
          },
          onClose: function(){
            alert('Anda menutup jendela pembayaran tanpa menyelesaikan pembayaran.');
          }
        });
      };
    </script>
    @endpush
</x-app-layout>
