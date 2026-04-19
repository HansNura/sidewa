<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UmkmController extends Controller
{
    /**
     * Index Dashboard Lapak (Tab Produk / Kategori)
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'produk');

        // ==== STATISTICS ====
        $totalProducts = Product::count();
        $totalActive = Product::active()->count();
        $totalDraft = Product::where('status', 'nonaktif')->count();
        $totalCategories = ProductCategory::count();
        // ====================

        if ($tab === 'kategori') {
            $categories = ProductCategory::withCount('products')->get();
            return view('pages.backoffice.umkm.index', compact('tab', 'categories', 'totalProducts', 'totalActive', 'totalDraft', 'totalCategories'));
        }

        // TAB PRODUK (Default)
        $query = Product::with('category')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('seller_name', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(15)->withQueryString();
        $categoriesList = ProductCategory::all();

        return view('pages.backoffice.umkm.index', compact('tab', 'products', 'categoriesList', 'totalProducts', 'totalActive', 'totalDraft', 'totalCategories'));
    }

    /**
     * Manajemen Produk
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'seller_name' => 'required|string|max:255',
            'seller_phone' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,13}$/'],
            'description_html' => 'required|string',
        ]);

        $product = $request->filled('id') ? Product::findOrFail($request->id) : new Product();

        $product->fill($request->only([
            'category_id', 'name', 'price', 'stock', 
            'seller_name', 'seller_phone', 'description_html', 'status'
        ]));

        if (!$request->filled('id')) {
            $product->slug = Str::slug($request->name) . '-' . mt_rand(100, 999);
        }

        $product->save();

        return back()->with('success', 'Produk UMKM berhasil disimpan!');
    }

    public function destroyProduct(Request $request)
    {
        $request->validate(['selected_ids' => 'required|string']);
        $ids = explode(',', $request->selected_ids);
        
        Product::whereIn('id', $ids)->delete();
        
        return back()->with('success', count($ids) . ' produk berhasil dihapus permanen.');
    }

    public function apiDetailProduct($id)
    {
        return response()->json(Product::with('category')->findOrFail($id));
    }


    /**
     * Manajemen Kategori
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $category = $request->filled('id') ? ProductCategory::findOrFail($request->id) : new ProductCategory();
        
        $category->name = $request->name;
        $category->icon = $request->icon;

        if (!$request->filled('id')) {
            $category->slug = Str::slug($request->name);
        }

        $category->save();

        return redirect()->route('admin.umkm.index', ['tab' => 'kategori'])->with('success', 'Kategori berhasil disimpan.');
    }

    public function destroyCategory($id)
    {
        $category = ProductCategory::findOrFail($id);
        
        // Proteksi Opsi 1 (Restrict). Namun sudah ditangani di Migration foreignId()->restrictOnDelete()
        // Kita tangkap manual disini agar errornya indah (UX Friendly)
        $productCount = $category->products()->count();
        
        if ($productCount > 0) {
            return back()->withErrors(['category' => 'Kategori "' . $category->name . '" tidak dapat dihapus karena masih memiliki ' . $productCount . ' produk aktif.']);
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
