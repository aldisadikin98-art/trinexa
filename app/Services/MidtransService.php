<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class MidtransService
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }

    public function createSnapToken($transactionDetails, $customerDetails, $itemDetails = [])
    {
        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        ];

        if (!empty($itemDetails)) {
            $params['item_details'] = $itemDetails;
        }

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (Exception $e) {
            throw new Exception("Midtrans Error: " . $e->getMessage());
        }
    }
}
