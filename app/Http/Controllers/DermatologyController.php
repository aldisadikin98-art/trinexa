<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkinContent;
use App\Models\UserSkinProgress;
use App\Models\UserBookmark;
use Illuminate\Support\Facades\Auth;

class DermatologyController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $skinType = $request->query('skin_type');
        $search = $request->query('search');

        $query = SkinContent::where('is_published', true);

        if ($type) {
            $query->where('type', $type);
        }

        if ($skinType) {
            // Also include contents that are for 'all' skin types
            $query->whereIn('skin_type', [$skinType, 'all']);
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $contents = $query->latest()->get();

        // Specific collections for the UI
        $featuredContents = SkinContent::where('is_published', true)->where('is_featured', true)->latest()->take(3)->get();
        $latestArticles = SkinContent::where('is_published', true)->where('type', 'article')->latest()->take(4)->get();
        $quickTips = SkinContent::where('is_published', true)->where('type', 'tip')->latest()->take(4)->get();
        $educationVideos = SkinContent::where('is_published', true)->where('type', 'video')->latest()->take(3)->get();

        // Stats
        $stats = [
            'articles' => SkinContent::where('is_published', true)->where('type', 'article')->count(),
            'tips' => SkinContent::where('is_published', true)->where('type', 'tip')->count(),
            'videos' => SkinContent::where('is_published', true)->where('type', 'video')->count(),
        ];

        return view('dermatology.index', compact(
            'contents', 'featuredContents', 'latestArticles', 'quickTips', 'educationVideos', 'stats', 'type', 'skinType', 'search'
        ));
    }

    public function show($slug)
    {
        $content = SkinContent::where('slug', $slug)->where('is_published', true)->firstOrFail();
        
        // Increase views
        $content->increment('views');

        $isCompleted = false;
        $isBookmarked = false;
        if (Auth::check()) {
            $userId = Auth::id();
            $isCompleted = UserSkinProgress::where('user_id', $userId)
                ->where('content_id', $content->id)
                ->exists();
            
            $isBookmarked = UserBookmark::where('user_id', $userId)
                ->where('skin_content_id', $content->id)
                ->exists();
        }

        return view('dermatology.show', compact('content', 'isCompleted', 'isBookmarked'));
    }

    public function complete(Request $request, SkinContent $content)
    {
        $userId = Auth::id();

        $progress = UserSkinProgress::firstOrCreate(
            ['user_id' => $userId, 'content_id' => $content->id],
            [
                'completed_at' => now(),
                'xp_earned' => $content->xp_reward
            ]
        );

        return response()->json([
            'success' => true, 
            'message' => 'Konten selesai dibaca',
            'xp_earned' => $content->xp_reward
        ]);
    }

    public function bookmark(Request $request, SkinContent $content)
    {
        $userId = Auth::id();
        
        $bookmark = UserBookmark::where('user_id', $userId)
            ->where('skin_content_id', $content->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['success' => true, 'is_bookmarked' => false, 'message' => 'Dihapus dari bookmark']);
        } else {
            UserBookmark::create([
                'user_id' => $userId,
                'skin_content_id' => $content->id
            ]);
            return response()->json(['success' => true, 'is_bookmarked' => true, 'message' => 'Disimpan ke bookmark']);
        }
    }
}
