<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\JdihCategory;
use App\Models\JdihDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JdihController extends Controller
{
    /**
     * Tampilan Utama (Tab Dokumen & Kategori JDIH)
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'dokumen');

        // ==== STATISTICS ====
        $totalDocs = JdihDocument::count();
        $totalBerlaku = JdihDocument::where('status', 'berlaku')->count();
        $totalDicabut = JdihDocument::where('status', 'dicabut')->count();

        // Count for specific categories based on names, just visual indicators on dashboard:
        $countPerdes = JdihDocument::whereHas('category', function ($q) {
            $q->where('slug', 'like', '%peraturan-desa%')->orWhere('name', 'like', '%Peraturan Desa%');
        })->count();
        $countSk = JdihDocument::whereHas('category', function ($q) {
            $q->where('slug', 'like', '%sk-kepala-desa%')->orWhere('name', 'like', '%SK%');
        })->count();
        // ====================

        if ($tab === 'kategori') {
            $categories = JdihCategory::withCount('documents')->get();
            return view('pages.backoffice.jdih.index', compact('tab', 'categories', 'totalDocs', 'totalBerlaku', 'totalDicabut', 'countPerdes', 'countSk'));
        }

        // TAB DOKUMEN (Default)
        $query = JdihDocument::with(['category', 'uploader'])->orderBy('established_date', 'desc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('document_number', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year')) {
            $query->whereYear('established_date', $request->year);
        }

        $documents = $query->paginate(15)->withQueryString();
        $categoriesList = JdihCategory::all();

        // Get unique years for the filter dropdown
        $driver = DB::getDriverName();
        $yearSelect = $driver === 'sqlite' ? "strftime('%Y', established_date)" : "YEAR(established_date)";
        $yearsList = JdihDocument::selectRaw("$yearSelect as year")->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('pages.backoffice.jdih.index', compact('tab', 'documents', 'categoriesList', 'yearsList', 'totalDocs', 'totalBerlaku', 'totalDicabut', 'countPerdes', 'countSk'));
    }

    /**
     * Manajemen Form Input & File Dokumen
     */
    public function storeDocument(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:jdih_categories,id',
            'title' => 'required|string|max:255',
            'document_number' => 'required|string|max:255',
            'established_date' => 'required|date',
            'status' => 'required|in:berlaku,dicabut,draft',
            'description' => 'nullable|string',
            'dokumen_file' => 'nullable|file|mimes:pdf|max:10240', // PDF max 10MB
        ]);

        $document = $request->filled('id') ? JdihDocument::findOrFail($request->id) : new JdihDocument();

        $document->fill($request->only([
            'category_id',
            'title',
            'document_number',
            'established_date',
            'status',
            'description'
        ]));

        if (!$request->filled('id')) {
            $document->uploader_id = $request->user()->id; // Assuming auth is active
        }

        // Handle File PDF Upload to public storage
        if ($request->hasFile('dokumen_file')) {
            // Delete old file if updating
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('dokumen_file');
            $fileName = Str::slug($request->document_number) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jdih', $fileName, 'public');

            $document->file_path = $path;
            $document->file_size = $file->getSize();
        } elseif (!$request->filled('id') && !$request->hasFile('dokumen_file')) {
            // User confirmed public JDIH nature, maybe allow empty file for drafts, but best to enforce if 'berlaku'
            // We allow empty mostly for testing purpose if they don't upload on edit
        }

        $document->save();

        return back()->with('success', 'Dokumen JDIH berhasil disimpan & diperbarui!');
    }

    public function destroyDocument(Request $request)
    {
        $request->validate(['selected_ids' => 'required|string']);
        $ids = explode(',', $request->selected_ids);

        $documents = JdihDocument::whereIn('id', $ids)->get();

        foreach ($documents as $doc) {
            if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }
            $doc->delete();
        }

        return back()->with('success', count($ids) . ' dokumen berhasil dihapus permanen.');
    }

    public function apiDetailDocument($id)
    {
        return response()->json(JdihDocument::with(['category', 'uploader'])->findOrFail($id));
    }


    /**
     * Manajemen Kategori JDIH
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = $request->filled('id') ? JdihCategory::findOrFail($request->id) : new JdihCategory();

        $category->name = $request->name;
        $category->description = $request->description;

        if (!$request->filled('id')) {
            $category->slug = Str::slug($request->name);
        }

        $category->save();

        return redirect()->route('admin.jdih.index', ['tab' => 'kategori'])->with('success', 'Kategori JDIH berhasil disimpan.');
    }

    public function destroyCategory($id)
    {
        $category = JdihCategory::findOrFail($id);

        // Manual trap to throw beautiful error (since DB matches restrict policy)
        $docCount = $category->documents()->count();

        if ($docCount > 0) {
            return back()->withErrors(['category' => 'Kategori "' . $category->name . '" tidak dapat dihapus karena masih digabungkan dengan ' . $docCount . ' dokumen aktif. Harap ubah kategori dokumen tersebut terlebih dahulu.']);
        }

        $category->delete();

        return back()->with('success', 'Kategori produk hukum berhasil dihapus.');
    }
}
