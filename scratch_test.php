<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\View;

try {
    $user = User::first();
    auth()->login($user);
    
    // Simulate request with sort=terlaris
    request()->merge(['sort' => 'terlaris']);
    
    $query = Product::active()->naturea()
        ->withCount('approvedReviews')
        ->withAvg('approvedReviews', 'rating');

    // Replicate controller match logic
    match (request('sort')) {
        'terlaris'       => $query->withCount('transactionItems')->orderByDesc('transaction_items_count'),
        'harga_terendah' => $query->orderBy('price', 'asc'),
        'harga_tertinggi'=> $query->orderBy('price', 'desc'),
        default          => $query->latest(),
    };
        
    $products = $query->paginate(9);
    $categories = ['Serum', 'Toner'];
    $cartCount = 0;

    echo "Attempting to render shop.index view with sort=terlaris...\n";
    $html = View::make('shop.index', compact('products', 'categories', 'cartCount'))->render();
    echo "Render successful! Length: " . strlen($html) . "\n";
    
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
