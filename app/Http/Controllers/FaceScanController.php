<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\FaceScanHistory;
use Exception;

class FaceScanController extends Controller
{
    public function index()
    {
        return view('user.face-scan.index');
    }

    public function analyze(Request $request)
    {
        // 1. Menerima upload file gambar wajah
        $request->validate([
            'face_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            // 2. Menyimpan gambar secara aman menggunakan Laravel Storage (disk public)
            $imagePath = $request->file('face_image')->store('face_scans', 'public');
            $imageUrl = Storage::disk('public')->url($imagePath);

            // Baca konten file untuk dikirim via HTTP Client
            $imageContent = file_get_contents($request->file('face_image')->getRealPath());
            $imageName = $request->file('face_image')->getClientOriginalName();

            // 3. Membuat request POST ke dummy endpoint
            try {
                $response = Http::timeout(5)->attach(
                    'image', $imageContent, $imageName
                )->post('https://api.dummyfacescan.com/analyze');

                if ($response->successful()) {
                    $resultJson = $response->json();
                } else {
                    throw new Exception("API Error Status: " . $response->status());
                }
            } catch (Exception $apiException) {
                // Karena endpoint adalah dummy, kita buat fallback dummy response
                // agar sistem tetap berjalan dan bisa dilihat hasilnya di tabel
                $resultJson = [
                    'oily' => rand(10, 40) . '%',
                    'dry' => rand(10, 40) . '%',
                    'acne' => rand(0, 20) . '%',
                    'skin_type' => 'Kombinasi'
                ];
            }

            // 4. Menyimpan hasil ke tabel FaceScanHistory
            $history = FaceScanHistory::create([
                'user_id' => $request->user()->id,
                'foto_url' => $imageUrl,
                'result_json' => $resultJson,
                'tipe_kulit' => $resultJson['skin_type'] ?? 'Unknown'
            ]);

            // 5. Mengembalikan hasil analisis ke tampilan Blade
            return view('user.face-scan.result', compact('history'));

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal menganalisis gambar: ' . $e->getMessage()]);
        }
    }
}
