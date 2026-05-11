<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    /**
     * Process checkout
     * 
     * @param \App\Models\User $user
     * @param array $items Array of ['product_id' => id, 'quantity' => qty]
     * @param string $paymentMethod e.g., 'wallet'
     * @return \App\Models\Transaction
     * @throws Exception
     */
    public function processCheckout($user, array $items, $paymentMethod)
    {
        // DB::transaction automatically handles commit on success and rollback on Exception
        return DB::transaction(function () use ($user, $items, $paymentMethod) {
            $totalAmount = 0;
            $transactionItems = [];

            // 1. Calculate total and verify stock
            foreach ($items as $item) {
                // Lock the product row for update to prevent race conditions during concurrent checkouts
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                if (!$product) {
                    throw new Exception("Product ID {$item['product_id']} not found.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Insufficient stock for product: {$product->name}.");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                // Decrease stock
                $product->stock -= $item['quantity'];
                $product->save();

                // Prepare transaction item data
                $transactionItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            // 2. Handle Wallet Payment Deduction
            if ($paymentMethod === 'wallet') {
                // Lock the wallet row to prevent race conditions
                $wallet = $user->wallet()->lockForUpdate()->first();

                if (!$wallet) {
                    throw new Exception("Wallet not found for this user.");
                }

                if ($wallet->balance < $totalAmount) {
                    throw new Exception("Insufficient wallet balance.");
                }

                // Deduct balance
                $wallet->balance -= $totalAmount;
                $wallet->save();

                // Log wallet transaction for purchase
                $user->wallet->walletTransactions()->create([
                    'type' => 'purchase',
                    'amount' => $totalAmount,
                    'description' => 'Pembayaran pesanan',
                    'status' => 'success'
                ]);

                // Auto-save logic (Sinergi Sisa Belanja)
                // Misal: pembulatan ke atas terdekat kelipatan 50.000 atau 10.000
                // Di sini kita bulatkan ke atas kelipatan 10.000 terdekat
                $roundedUp = ceil($totalAmount / 10000) * 10000;
                $autoSaveAmount = $roundedUp - $totalAmount;

                if ($autoSaveAmount > 0 && $wallet->balance >= $autoSaveAmount) {
                    $activeGoal = $user->savingGoals()->where('is_completed', false)->first();
                    if ($activeGoal) {
                        $wallet->balance -= $autoSaveAmount;
                        $wallet->save();

                        // Log auto-save
                        $user->wallet->walletTransactions()->create([
                            'type' => 'autosave',
                            'amount' => $autoSaveAmount,
                            'description' => 'Auto-save dari sisa belanja ke target: ' . $activeGoal->title,
                            'status' => 'success'
                        ]);

                        $activeGoal->current_amount += $autoSaveAmount;
                        if ($activeGoal->current_amount >= $activeGoal->target_amount) {
                            $activeGoal->is_completed = true;
                        }
                        $activeGoal->save();
                    }
                }
            }

            // 3. Create Transaction
            $transactionStatus = $paymentMethod === 'wallet' ? 'paid' : 'pending';

            $transaction = $user->transactions()->create([
                'total_amount' => $totalAmount,
                'status' => $transactionStatus,
            ]);

            // 4. Create Transaction Items
            $transaction->items()->createMany($transactionItems);

            return $transaction;
        });
    }
}
