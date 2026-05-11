<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        // Security check signature key
        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if ($notification->signature_key !== $validSignatureKey) {
            return response(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::find($notification->order_id);

        if (!$transaction) {
            return response(['message' => 'Transaction not found'], 404);
        }

        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'pending';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'paid';
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'paid';
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();

        Log::info("Midtrans Webhook Received", ['order_id' => $notification->order_id, 'status' => $transactionStatus]);

        return response()->json(['message' => 'Success']);
    }
}
