<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KareblaProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminKareblaProductController extends Controller
{
    public function index(Request $request)
    {
        $query = KareblaProduct::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(15);
        return view('admin.karebla.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.karebla.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'collection' => 'nullable|string|max:255',
            'description' => 'required|string',
            'coin_price' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        $data = $request->except(['specs_keys', 'specs_values']);
        $data['slug'] = Str::slug($request->name) . '-' . uniqid();
        $data['is_active'] = $request->has('is_active');

        // Handle Specs
        $specs = [];
        if ($request->has('specs_keys') && is_array($request->specs_keys)) {
            foreach ($request->specs_keys as $index => $key) {
                if (!empty($key) && isset($request->specs_values[$index])) {
                    $specs[$key] = $request->specs_values[$index];
                }
            }
        }
        $data['specs'] = $specs;

        // Image upload handling (simplified for now, using dummy images if empty)
        $data['images'] = ['https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&q=80&w=800'];

        KareblaProduct::create($data);

        return redirect()->route('admin.karebla.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(KareblaProduct $produk)
    {
        return view('admin.karebla.products.edit', compact('produk'));
    }

    public function update(Request $request, KareblaProduct $produk)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'collection' => 'nullable|string|max:255',
            'description' => 'required|string',
            'coin_price' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'badge' => 'nullable|string|max:50',
        ]);

        $data = $request->except(['specs_keys', 'specs_values']);
        $data['is_active'] = $request->has('is_active');
        
        if ($produk->name !== $request->name) {
            $data['slug'] = Str::slug($request->name) . '-' . uniqid();
        }

        $specs = [];
        if ($request->has('specs_keys') && is_array($request->specs_keys)) {
            foreach ($request->specs_keys as $index => $key) {
                if (!empty($key) && isset($request->specs_values[$index])) {
                    $specs[$key] = $request->specs_values[$index];
                }
            }
        }
        $data['specs'] = $specs;

        $produk->update($data);

        return redirect()->route('admin.karebla.produk.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function toggle(KareblaProduct $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Status produk berhasil diubah.');
    }

    public function destroy(KareblaProduct $produk)
    {
        $produk->delete();
        return redirect()->route('admin.karebla.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
