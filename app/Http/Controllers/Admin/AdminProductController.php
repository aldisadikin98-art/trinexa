<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::naturea();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products   = $query->latest()->paginate(15);
        $categories = ['Serum', 'Toner', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Treatment'];

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ['Serum', 'Toner', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Treatment'];
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'nullable|string',
            'price'                 => 'required|numeric|min:0',
            'stock'                 => 'required|integer|min:0',
            'category'              => 'required|string',
            'ingredients'           => 'nullable|string',
            'skin_type'             => 'nullable|array',
            'skin_type_not_suitable'=> 'nullable|string',
            'usage_instructions'    => 'nullable|string',
            'benefits'              => 'nullable|string',
            'bpom_number'           => 'nullable|string',
            'image_url'             => 'nullable|url',
            'images.*'              => 'nullable|image|max:2048',
            'reward_points'         => 'nullable|integer|min:0',
        ]);

        $images = [];
        if ($request->filled('image_url')) {
            $images[] = $request->image_url;
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = asset('storage/' . $img->store('products', 'public'));
            }
        }

        $ingredientsArr = [];
        if ($request->filled('ingredients')) {
            $ingredientsArr = array_filter(array_map('trim', explode("\n", $request->ingredients)));
        }

        $product = Product::create([
            'name'                   => $validated['name'],
            'slug'                   => Str::slug($validated['name']),
            'description'            => $validated['description'] ?? null,
            'price'                  => $validated['price'],
            'stock'                  => $validated['stock'],
            'type'                   => 'skincare',
            'brand'                  => 'naturea',
            'category'               => $validated['category'],
            'image_url'              => $images[0] ?? null,
            'images'                 => $images,
            'ingredients'            => $ingredientsArr,
            'skin_type'              => $validated['skin_type'] ?? [],
            'skin_type_not_suitable' => $validated['skin_type_not_suitable'] ?? null,
            'usage_instructions'     => $validated['usage_instructions'] ?? null,
            'benefits'               => $validated['benefits'] ?? null,
            'bpom_number'            => $validated['bpom_number'] ?? null,
            'reward_points'          => $validated['reward_points'] ?? 0,
            'is_active'              => true,
        ]);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = ['Serum', 'Toner', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Treatment'];
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'price'                 => 'required|numeric|min:0',
            'stock'                 => 'required|integer|min:0',
            'category'              => 'required|string',
            'skin_type'             => 'nullable|array',
            'images.*'              => 'nullable|image|max:2048',
        ]);

        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = asset('storage/' . $img->store('products', 'public'));
            }
        }

        $ingredientsArr = $product->ingredients ?? [];
        if ($request->filled('ingredients')) {
            $ingredientsArr = array_filter(array_map('trim', explode("\n", $request->ingredients)));
        }

        $product->update([
            'name'                   => $request->name,
            'slug'                   => Str::slug($request->name),
            'description'            => $request->description,
            'price'                  => $request->price,
            'stock'                  => $request->stock,
            'category'               => $request->category,
            'images'                 => $images,
            'image_url'              => $images[0] ?? $product->image_url,
            'ingredients'            => $ingredientsArr,
            'skin_type'              => $request->skin_type ?? [],
            'skin_type_not_suitable' => $request->skin_type_not_suitable,
            'usage_instructions'     => $request->usage_instructions,
            'benefits'               => $request->benefits,
            'bpom_number'            => $request->bpom_number,
            'reward_points'          => $request->reward_points ?? 0,
        ]);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk berhasil {$status}.");
    }
}
