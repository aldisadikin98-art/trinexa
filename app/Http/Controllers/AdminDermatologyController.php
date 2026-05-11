<?php

namespace App\Http\Controllers;

use App\Models\SkinContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminDermatologyController extends Controller
{
    public function index(Request $request)
    {
        $query = SkinContent::query();

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('is_published', $request->status == 'published');
        }
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $contents = $query->latest()->paginate(10)->withQueryString();

        return view('admin.dermatology.index', compact('contents'));
    }

    public function create()
    {
        return view('admin.dermatology.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:article,tip,video',
            'skin_type' => 'required|in:all,oily,dry,combination,sensitive',
            'content' => 'required_if:type,article,tip',
            'video_url' => 'required_if:type,video|nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'xp_reward' => 'required|integer|min:0',
            'read_time' => 'required_if:type,article,tip|integer|min:1',
        ]);

        $data = $request->except(['thumbnail', 'is_featured', 'is_published']);
        $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');

        if ($request->type == 'video') {
            $data['content'] = $request->content ?? '';
            $data['read_time'] = 0;
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('dermatology', 'public');
        }

        // Set default category to 1 (Assuming a default exists)
        $data['category_id'] = 1;

        SkinContent::create($data);

        return redirect()->route('admin.dermatology.index')->with('success', 'Konten berhasil ditambahkan.');
    }

    public function edit(SkinContent $content)
    {
        return view('admin.dermatology.form', compact('content'));
    }

    public function update(Request $request, SkinContent $content)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:article,tip,video',
            'skin_type' => 'required|in:all,oily,dry,combination,sensitive',
            'content' => 'required_if:type,article,tip',
            'video_url' => 'required_if:type,video|nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'xp_reward' => 'required|integer|min:0',
            'read_time' => 'required_if:type,article,tip|integer|min:1',
        ]);

        $data = $request->except(['thumbnail', 'is_featured', 'is_published']);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');

        if ($request->type == 'video') {
            $data['content'] = $request->content ?? '';
            $data['read_time'] = 0;
        }

        if ($request->hasFile('thumbnail')) {
            if ($content->thumbnail) {
                Storage::disk('public')->delete($content->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('dermatology', 'public');
        }

        $content->update($data);

        return redirect()->route('admin.dermatology.index')->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(SkinContent $content)
    {
        if ($content->thumbnail) {
            Storage::disk('public')->delete($content->thumbnail);
        }
        $content->delete();

        return redirect()->route('admin.dermatology.index')->with('success', 'Konten berhasil dihapus.');
    }

    public function toggleFeatured(SkinContent $content)
    {
        $content->update(['is_featured' => !$content->is_featured]);
        return response()->json(['success' => true]);
    }

    public function togglePublished(SkinContent $content)
    {
        $content->update(['is_published' => !$content->is_published]);
        return response()->json(['success' => true]);
    }
}
