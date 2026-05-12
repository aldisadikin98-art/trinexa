<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\Product;
use App\Services\GroqService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatAIController extends Controller
{
    public function __construct(protected GroqService $groq) {}

    public function index()
    {
        $sessions = ChatSession::where('user_id', auth()->id())
            ->withCount('messages')
            ->latest()
            ->get();

        return view('konsultasi.chat.index', compact('sessions'));
    }

    public function createSession()
    {
        $session = ChatSession::create([
            'user_id' => auth()->id(),
            'title'   => 'Chat Baru',
        ]);

        return redirect()->route('konsultasi.chat.show', $session);
    }

    public function show(ChatSession $session)
    {
        abort_if($session->user_id !== auth()->id(), 403);

        $messages = $session->messages()->oldest()->get();
        $sessions = ChatSession::where('user_id', auth()->id())->latest()->get();

        return view('konsultasi.chat.show', compact('session', 'messages', 'sessions'));
    }

    public function send(Request $request, ChatSession $session)
    {
        abort_if($session->user_id !== auth()->id(), 403);

        $request->validate(['message' => 'required|string|max:1000']);

        $user    = auth()->user();
        $lang    = $request->input('lang', 'id');
        $message = $request->input('message');

        // Save user message
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'role'            => 'user',
            'content'         => $message,
            'language'        => $lang,
        ]);

        // Auto-title from first message
        if ($session->messages()->count() === 1) {
            $session->update(['title' => Str::limit($message, 50)]);
        }

        // Build context history
        $history = $session->messages()
            ->oldest()
            ->get()
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
            ->toArray();

        // Get AI response
        $systemPrompt = $this->buildSystemPrompt($user, $lang);
        $aiResponse   = $this->groq->chat($history, $systemPrompt);

        // Extract product recommendations
        $products = $this->extractProductRecommendations($aiResponse);

        // Save AI message
        $aiMessage = ChatMessage::create([
            'chat_session_id' => $session->id,
            'role'            => 'assistant',
            'content'         => $aiResponse,
            'products'        => $products,
            'language'        => $lang,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => $aiMessage->content,
            'products' => $products,
        ]);
    }

    public function quickChat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $user    = auth()->user();
        $lang    = $request->input('lang', 'id');
        $history = $request->input('history', []);

        $systemPrompt = $this->buildSystemPrompt($user, $lang);
        $messages     = array_merge($history, [['role' => 'user', 'content' => $request->message]]);
        $aiResponse   = $this->groq->chat($messages, $systemPrompt);
        $products     = $this->extractProductRecommendations($aiResponse);

        return response()->json([
            'success'  => true,
            'message'  => $aiResponse,
            'products' => $products,
        ]);
    }

    public function destroy(ChatSession $session)
    {
        abort_if($session->user_id !== auth()->id(), 403);
        $session->delete();

        return response()->json(['success' => true]);
    }

    private function buildSystemPrompt($user, string $lang = 'id'): string
    {
        $latestScan  = $user->faceScanResults()->latest()->first();
        $skinType    = $latestScan?->skin_type ?? 'belum diketahui (sarankan Face Scan)';

        $purchaseHistory = $user->transactions()
            ->where('status', 'selesai')
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('product.name')
            ->filter()
            ->unique()
            ->implode(', ') ?: 'belum ada';

        $products = Product::where('is_active', true)
            ->get()
            ->map(fn($p) => "{$p->name} (Rp" . number_format($p->price, 0, ',', '.') . ")")
            ->implode(', ');

        $langInstruction = $lang === 'en'
            ? 'Always respond in English.'
            : 'Selalu balas dalam Bahasa Indonesia yang ramah dan santai.';

        return "Kamu adalah Truevera, konsultan skincare personal yang ramah dan ahli dari Naturea — brand skincare alami milik Trinexa.

Kepribadianmu:
- Ramah, hangat, dan supportif seperti sahabat terbaik
- Ahli skincare tapi tidak menggurui
- Suka pakai emoji yang relevan 🌸✨💧
- Selalu empati terhadap keluhan user
- {$langInstruction}

Informasi user:
- Nama: {$user->name}
- Jenis kulit: {$skinType}
- Produk yang pernah dibeli: {$purchaseHistory}

Produk Naturea yang tersedia:
{$products}

Aturan penting:
- Rekomendasikan produk Naturea jika relevan (max 3 produk)
- Jangan rekomendasikan brand skincare lain
- Jika jenis kulit belum diketahui, sarankan Face Scan terlebih dahulu
- Untuk kondisi kulit serius (alergi parah, luka, infeksi) → sarankan dokter kulit
- Jika merekomendasikan produk, tuliskan dalam format: [PRODUK: nama_produk]
- Jawaban maksimal 3 paragraf, padat dan berguna";
    }

    private function extractProductRecommendations(string $response): array
    {
        preg_match_all('/\[PRODUK:\s*([^\]]+)\]/i', $response, $matches);

        if (empty($matches[1])) return [];

        $products = [];
        foreach ($matches[1] as $name) {
            $product = Product::where('name', 'LIKE', '%' . trim($name) . '%')
                ->where('is_active', true)
                ->first();

            if ($product) {
                $products[] = [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'price' => $product->price,
                    'image' => $product->image_url ?? $product->primary_image,
                    'slug'  => $product->slug,
                ];
            }
        }

        return $products;
    }
}
