<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\PublicPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Tampilan Dasar
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'list');

        // Sumary Cards
        $totalPages = PublicPage::count();
        $totalPublished = PublicPage::where('status', 'publish')->count();
        $totalDraft = PublicPage::where('status', 'draft')->count();
        $totalSystem = PublicPage::where('type', 'system')->count();

        // Data Models
        $query = PublicPage::with('parent')->orderBy('order', 'asc');
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pages = $query->get();

        // Hierarchical structure for tab_navigasi
        $rootPages = PublicPage::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();

        return view('pages.backoffice.halaman.index', compact(
            'tab', 'totalPages', 'totalPublished', 'totalDraft', 'totalSystem', 'pages', 'rootPages'
        ));
    }

    /**
     * Menyimpan / Memperbarui Halaman
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content_html' => 'nullable|string',
            'status' => 'required|in:draft,publish',
            'parent_id' => 'nullable|exists:public_pages,id',
            'slug_input' => 'nullable|string|max:255',
        ]);

        $page = null;
        if ($request->filled('id')) {
            $page = PublicPage::findOrFail($request->id);
            
            // System pages slug protection
            if ($page->type == 'system') {
                $slug = $page->slug;
            } else {
                $slug = $request->filled('slug_input') ? Str::slug($request->slug_input) : Str::slug($request->title);
            }
        } else {
            $page = new PublicPage();
            $slug = $request->filled('slug_input') ? Str::slug($request->slug_input) : Str::slug($request->title);
            
            // Check slug uniqueness for new custom pages
            $baseSlug = $slug;
            $count = 1;
            while (PublicPage::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
        }

        $page->title = $request->title;
        $page->slug = $slug;
        $page->content_html = $request->content_html;
        $page->status = $request->status;
        $page->parent_id = $request->parent_id;
        $page->meta_title = $request->meta_title;
        $page->meta_description = $request->meta_description;
        
        // Auto-order for new pages
        if (!$request->filled('id')) {
            $maxOrder = PublicPage::where('parent_id', $request->parent_id)->max('order');
            $page->order = $maxOrder ? $maxOrder + 1 : 1;
        }

        $page->save();

        return back()->with('success', 'Halaman berhasil disimpan.');
    }

    /**
     * Detail API for Drawer/Modal
     */
    public function apiDetail($id)
    {
        $page = PublicPage::with('parent')->findOrFail($id);
        
        // Compute full URL path
        $fullPath = '/' . $page->slug;
        if ($page->parent_id && $page->parent) {
            $fullPath = '/' . $page->parent->slug . $fullPath;
        }
        
        $page->fullPath = $fullPath;
        return response()->json($page);
    }

    /**
     * Hapus Halaman Massal (Dengan Proteksi System)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:draft,delete',
            'selected_ids' => 'required|string'
        ]);

        $ids = explode(',', $request->selected_ids);

        if ($request->action == 'delete') {
            // Protect system pages
            $deletedCount = PublicPage::whereIn('id', $ids)->where('type', 'custom')->delete();
            $skippedCount = count($ids) - $deletedCount;
            
            $msg = "Berhasil menghapus $deletedCount halaman.";
            if ($skippedCount > 0) $msg .= " ($skippedCount dilewati karena tipe Sistem).";
            
            return back()->with('success', $msg);
        }

        if ($request->action == 'draft') {
            PublicPage::whereIn('id', $ids)->update(['status' => 'draft']);
            return back()->with('success', "Berhasil mengubah status halaman menjadi Draft.");
        }
    }

    /**
     * Ubah Urutan Menu (Arrow Based Navigation)
     */
    public function moveOrder($id, $direction)
    {
        $page = PublicPage::findOrFail($id);
        
        $swapPage = null;
        if ($direction == 'up') {
            $swapPage = PublicPage::where('parent_id', $page->parent_id)
                ->where('order', '<', $page->order)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $swapPage = PublicPage::where('parent_id', $page->parent_id)
                ->where('order', '>', $page->order)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($swapPage) {
            $currentOrder = $page->order;
            $page->order = $swapPage->order;
            $swapPage->order = $currentOrder;
            
            $page->save();
            $swapPage->save();
        }

        return back()->with('success', 'Urutan berhasil diperbarui.');
    }
}
