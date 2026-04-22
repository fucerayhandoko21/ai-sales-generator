<?php

namespace App\Http\Controllers;

use App\Models\SalesPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // ✅ tambahkan ini

class SalesPageController extends Controller
{
    public function index()
    {
        $pages = SalesPage::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('dashboard', compact('pages'));
    }

    public function show(SalesPage $salesPage)
    {
        abort_if($salesPage->user_id !== Auth::id(), 403);

        return view('sales-pages.show', compact('salesPage'));
    }

    public function edit(SalesPage $salesPage)
    {
        abort_if($salesPage->user_id !== Auth::id(), 403);

        return view('sales-pages.edit', compact('salesPage'));
    }

    public function update(Request $request, SalesPage $salesPage)
    {
        abort_if($salesPage->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'product_name'         => 'required|string|max:255',
            'description'          => 'required|string',
            'key_features'         => 'required|string',
            'target_audience'      => 'required|string|max:255',
            'price'                => 'required|string|max:100',
            'unique_selling_points'=> 'nullable|string',
            'template'             => 'nullable|string|in:modern,minimal,bold',
        ]);

        $productController = new ProductController();
        $generatedHtml = $productController->generateSalesPagePublic($validated);

        if (!$generatedHtml) {
            return back()->withErrors(['api' => 'Gagal re-generate. Coba lagi.']);
        }

        $salesPage->update([
            ...$validated,
            'generated_html' => $generatedHtml,
        ]);

        return redirect()
            ->route('pages.show', $salesPage)
            ->with('success', 'Sales page berhasil di-update!');
    }

    public function destroy(SalesPage $salesPage)
    {
        abort_if($salesPage->user_id !== Auth::id(), 403);

        $salesPage->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Sales page dihapus.');
    }
}