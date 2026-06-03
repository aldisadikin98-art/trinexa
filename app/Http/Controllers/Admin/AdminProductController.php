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
        $query = Product::naturea()
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating');

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
        try {
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
            'images.*'              => 'nullable|image|max:5120',
            'reward_points'         => 'nullable|integer|min:0',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = $this->compressImageToBase64($img);
            }
        } elseif ($request->filled('image_url')) {
            $images[] = $request->image_url;
        }

        $ingredientsArr = [];
        if ($request->filled('ingredients')) {
            $ingredientsArr = array_values(array_filter(array_map('trim', explode("\n", $request->ingredients))));
        }

        // Check for duplicate name manually to give friendly error
        $exists = Product::where('name', $validated['name'])->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['name' => 'Nama produk "' . $validated['name'] . '" sudah ada. Gunakan nama lain.']);
        }

        try {
            $product = Product::create([
                'name'                   => $validated['name'],
                'slug'                   => Str::slug($validated['name']),
                'description'            => $validated['description'] ?? null,
                'price'                  => $validated['price'],
                'stock'                  => $validated['stock'],
                'type'                   => 'skincare',
                'brand'                  => 'naturea',
                'category'               => $validated['category'],
                'image_url'              => (!$request->hasFile('images') && $request->filled('image_url')) ? $request->image_url : null,
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
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['name' => 'Gagal menyimpan produk: ' . $e->getMessage()]);
        }

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan.');
        } catch (\Throwable $th) {
            // Debugging unhandled errors (500)
            dd("SYSTEM CRASH DETECTED:", $th->getMessage(), "File: " . $th->getFile(), "Line: " . $th->getLine());
        }
    }

    public function edit(Product $produk)
    {
        $product = $produk;
        $categories = ['Serum', 'Toner', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Treatment'];
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $produk)
    {
        $product = $produk;
        $request->validate([
            'name'                  => 'required|string|max:255|unique:products,name,' . $product->id,
            'price'                 => 'required|numeric|min:0',
            'stock'                 => 'required|integer|min:0',
            'category'              => 'required|string',
            'skin_type'             => 'nullable|array',
            'images.*'              => 'nullable|image|max:2048',
        ]);

        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $img) {
                $images[] = $this->compressImageToBase64($img);
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
            'image_url'              => null,
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

    public function destroy(Product $produk)
    {
        $produk->delete();
        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk berhasil {$status}.");
    }

    /**
     * Compress and resize an uploaded image to base64.
     * Max 800px dimension, 70% JPEG quality to keep base64 size manageable.
     */
    private function compressImageToBase64($uploadedFile): string
    {
        $maxDimension = 800;
        $quality = 70;

        $originalPath = $uploadedFile->getRealPath();
        $mime = $uploadedFile->getMimeType();

        // Fallback function to raw base64
        $getRawBase64 = function() use ($originalPath, $mime) {
            $b64 = base64_encode(file_get_contents($originalPath));
            return 'data:' . $mime . ';base64,' . $b64;
        };

        // Check if GD extension is fully available
        if (!function_exists('imagecreatefromjpeg') || !function_exists('imagecreatetruecolor') || !function_exists('imagejpeg')) {
            return $getRawBase64();
        }

        try {
            $imageInfo = @getimagesize($originalPath);
            if (!$imageInfo) {
                return $getRawBase64();
            }

            $actualMime = $imageInfo['mime'] ?? $mime;

            // Create GD image resource from uploaded file
            $source = match ($actualMime) {
                'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($originalPath),
                'image/png' => @imagecreatefrompng($originalPath),
                'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($originalPath) : null,
                default => null,
            };

            if (!$source) {
                return $getRawBase64();
            }

            $origWidth = imagesx($source);
            $origHeight = imagesy($source);

            // Calculate new dimensions
            if ($origWidth > $maxDimension || $origHeight > $maxDimension) {
                if ($origWidth >= $origHeight) {
                    $newWidth = $maxDimension;
                    $newHeight = (int) round($origHeight * ($maxDimension / $origWidth));
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = (int) round($origWidth * ($maxDimension / $origHeight));
                }
            } else {
                $newWidth = $origWidth;
                $newHeight = $origHeight;
            }

            // Resize
            $resized = @imagecreatetruecolor($newWidth, $newHeight);
            if (!$resized) {
                imagedestroy($source);
                return $getRawBase64();
            }

            // Preserve transparency for PNG
            if ($actualMime === 'image/png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }
            
            @imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            // Output to buffer as JPEG for smaller size
            ob_start();
            @imagejpeg($resized, null, $quality);
            $compressedData = ob_get_clean();

            imagedestroy($source);
            imagedestroy($resized);

            if (empty($compressedData)) {
                return $getRawBase64();
            }

            return 'data:image/jpeg;base64,' . base64_encode($compressedData);
        } catch (\Throwable $e) {
            // If any error occurs (e.g. memory exhaustion), fallback to raw base64
            return $getRawBase64();
        }
    }
}
