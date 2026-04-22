<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\SalesPage;
use Gemini\Laravel\Facades\Gemini; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function generateSalesPagePublic(array $data): ?string
    {
        return $this->generateSalesPage($data);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name'          => 'required|string|max:255',
            'description'           => 'required|string',
            'key_features'          => 'required|string',
            'target_audience'       => 'required|string|max:255',
            'price'                 => 'required|string|max:100',
            'unique_selling_points' => 'nullable|string',
            'template'              => 'nullable|string|in:modern,minimal,bold',
        ]);

        // Generate via Gemini AI
        $generatedHtml = $this->generateSalesPage($validated);

        if (!$generatedHtml) {
            return back()
                ->withInput()
                ->withErrors(['api' => 'Gagal generate sales page menggunakan Gemini. Silakan coba lagi.']);
        }

        // Simpan ke database
        $salesPage = SalesPage::create([
            'user_id'               => Auth::id(),
            'product_name'          => $validated['product_name'],
            'description'           => $validated['description'],
            'key_features'          => $validated['key_features'],
            'target_audience'       => $validated['target_audience'],
            'price'                 => $validated['price'],
            'unique_selling_points' => $validated['unique_selling_points'] ?? null,
            'generated_html'        => $generatedHtml,
            'template'              => $validated['template'] ?? 'modern',
        ]);

        return redirect()
            ->route('pages.show', $salesPage)
            ->with('success', 'Sales page berhasil dibuat dengan AI!');
    }


private function generateSalesPage(array $data): ?string
{
    try {
        $prompt = $this->buildPrompt($data);
        
        // Ambil API Key dari .env (Pastikan sudah php artisan config:clear)
        $apiKey = env('GEMINI_API_KEY'); 

        // Gunakan model 2.0 Flash Lite (Lebih stabil & jarang antre dibanding 1.5)
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite:generateContent?key=" . $apiKey;

        $response = Http::withoutVerifying() // Solusi error SSL di local/Windows
            ->withHeaders(['Content-Type' => 'application/json'])
            ->timeout(120) // Waktu tunggu lebih lama untuk generate HTML
            ->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 5000, // Memastikan HTML tidak terpotong
                ]
            ]);

        // Tangani Error Overload (503) atau Kuota (429)
        if ($response->status() == 503 || $response->status() == 429) {
            Log::warning("Gemini Busy/Limit: " . $response->body());
            return "Server Google sedang sibuk atau kuota habis. Silakan coba lagi dalam 1 menit.";
        }

        if ($response->failed()) {
            Log::error("Gemini Error: " . $response->body());
            return null;
        }

        $result = $response->json();
        
        // Ambil teks dari struktur JSON Google
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if ($text) {
            // Bersihkan markdown ```html agar tidak muncul di layar
            $text = preg_replace('/^```html\s*/i', '', trim($text));
            $text = preg_replace('/\s*```$/', '', $text);
            return trim($text);
        }

        return null;

    } catch (\Exception $e) {
        Log::error('Integrasi Gemini Gagal: ' . $e->getMessage());
        return null;
    }
}
    private function buildPrompt(array $data): string
    {
        $template = $data['template'] ?? 'modern';

        $templateConfig = match($template) {
            'minimal' => [
                'style'     => 'Clean minimalist design. White background (#FFFFFF). Black text (#111111). Single accent color: #000000. Lots of whitespace. Simple sans-serif typography.',
                'tone'      => 'calm, clear, trustworthy, straightforward',
                'cta_color' => '#111111',
                'hero_bg'   => '#F9F9F9',
            ],
            'bold' => [
                'style'     => 'Bold, high-energy design. Dark background (#0F0F0F). Bright accent: #FF4D00. Large typography. Strong contrast. Aggressive layout.',
                'tone'      => 'urgent, powerful, exciting, action-driven',
                'cta_color' => '#FF4D00',
                'hero_bg'   => '#0F0F0F',
            ],
            default => [
                'style'     => 'Modern professional design. White background. Gradient accent from #6366F1 to #8B5CF6 (indigo-purple). Clean typography. Subtle shadows.',
                'tone'      => 'professional, confident, inspiring, benefit-focused',
                'cta_color' => '#6366F1',
                'hero_bg'   => 'linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%)',
            ],
        };

        // Ekstrak variabel
        $productName    = $data['product_name'];
        $description    = $data['description'];
        $keyFeatures    = $data['key_features'];
        $targetAudience = $data['target_audience'];
        $price          = $data['price'];
        $usp            = $data['unique_selling_points'] ?? 'Not specified — infer from product data';
        $style          = $templateConfig['style'];
        $tone           = $templateConfig['tone'];
        $ctaColor       = $templateConfig['cta_color'];
        $heroBg         = $templateConfig['hero_bg'];

        return <<<PROMPT
You are a world-class direct-response copywriter AND frontend developer.
Your task: generate a complete, self-contained HTML sales page that converts visitors into buyers.

PRODUCT DATA:
Product Name: {$productName}
Description: {$description}
Key Features: {$keyFeatures}
Target Audience: {$targetAudience}
Price: {$price}
USP: {$usp}

DESIGN:
Style: {$style}
Tone: {$tone}
CTA Color: {$ctaColor}
Hero BG: {$heroBg}

TECHNICAL RULES:
- Output ONLY raw HTML (starting with <style> or <div>)
- DO NOT use Markdown code blocks (no ```html)
- Use ONLY inline CSS and one <style> block
- Make it mobile responsive
- Write persuasive copy, NO Lorem Ipsum

BEGIN OUTPUT:
PROMPT;
    }
}