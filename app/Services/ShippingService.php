<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class ShippingService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter/');
        $this->apiKey = env('RAJAONGKIR_API_KEY');
    }

    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get($this->baseUrl . 'province');

        if ($response->successful()) {
            return $response->json()['rajaongkir']['results'];
        }

        throw new Exception("Failed to fetch provinces: " . $response->body());
    }

    public function getCities($provinceId = null)
    {
        $url = $this->baseUrl . 'city';
        if ($provinceId) {
            $url .= '?province=' . $provinceId;
        }

        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get($url);

        if ($response->successful()) {
            return $response->json()['rajaongkir']['results'];
        }

        throw new Exception("Failed to fetch cities: " . $response->body());
    }

    public function calculateCost($origin, $destination, $weight, $courier)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->post($this->baseUrl . 'cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier
        ]);

        if ($response->successful()) {
            return $response->json()['rajaongkir']['results'][0]['costs'];
        }

        throw new Exception("Failed to calculate shipping cost: " . $response->body());
    }
}
