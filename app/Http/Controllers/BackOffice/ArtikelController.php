<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    /**
     * Tampilan Dasar
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'artikel');

        // Sumary Cards
        $totalArticles = Article::count();
        $totalPublished = Article::where('status', 'publish')->count();
        $totalDraftScheduled = Article::whereIn('status', ['draft', 'schedule'])->count();
        $totalArchived = Article::where('status', 'archived')->count();

        // Data Models
        $categories = ArticleCategory::withCount('articles')->get();
        $tags = Tag::withCount('articles')->get();

        // Filter and Search Articles
        $query = Article::with(['category', 'tags', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $articles = $query->paginate(20);

        return view('pages.backoffice.artikel.index', compact(
            'tab',
            'totalArticles', 'totalPublished', 'totalDraftScheduled', 'totalArchived',
            'categories', 'tags', 'articles'
        ));
    }

    /**
     * Tambah Kategori
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:article_categories,nama_kategori'
        ]);

        ArticleCategory::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori)
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Hapus Kategori
     */
    public function destroyCategory($id)
    {
        $category = ArticleCategory::findOrFail($id);
        // Nullkan referensi artikel
        Article::where('kategori_id', $category->id)->update(['kategori_id' => null]);
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus. Artikel terkait menjadi Uncategorized.');
    }

    /**
     * Tambah Tag Baru
     */
    public function storeTag(Request $request)
    {
        $request->validate([
            'nama_tag' => 'required|string|max:100|unique:tags,nama_tag'
        ]);

        Tag::create([
            'nama_tag' => $request->nama_tag,
            'slug' => Str::slug($request->nama_tag)
        ]);

        return back()->with('success', 'Tag baru berhasil ditambahkan.');
    }

    /**
     * Hapus Tag
     */
    public function destroyTag($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->articles()->detach(); // Lepas relasi pivot
        $tag->delete();

        return back()->with('success', 'Tag berhasil dihapus.');
    }

    /**
     * Menyimpan / Memperbarui Artikel (Dengan Rich Text Processing)
     */
    public function store(Request $request)
    {
        // $request->id means it's an update operation
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten_html' => 'required|string',
            'status' => 'required|in:draft,publish,schedule',
            'kategori_id' => 'nullable|exists:article_categories,id',
            'published_at' => 'nullable|date',
            'tags_input' => 'nullable|string', // comma separated strings
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $article = null;
        if ($request->filled('id')) {
            $article = Article::findOrFail($request->id);
            $slug = $article->judul == $request->judul ? $article->slug : Str::slug($request->judul) . '-' . time();
        } else {
            $article = new Article();
            $slug = Str::slug($request->judul) . '-' . time();
            $article->user_id = auth()->id() ?? 1; // Opsional: Auth ID fallback
        }

        if ($request->hasFile('cover_image')) {
            if ($article->cover_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($article->cover_image);
            }
            $article->cover_image = $request->file('cover_image')->store('berita/cover', 'public');
        }

        $article->judul = $request->judul;
        $article->slug = $request->filled('slug_input') ? Str::slug($request->slug_input) : $slug;
        $article->konten_html = $request->konten_html;
        $article->ringkasan = Str::limit(strip_tags($request->konten_html), 150);
        $article->status = $request->status;
        
        if ($request->status == 'schedule') {
            $article->published_at = $request->published_at;
        } elseif ($request->status == 'publish' && !$article->published_at) {
            $article->published_at = now();
        }

        $article->kategori_id = $request->kategori_id;
        $article->save();

        // Konsep "Hybrid Tag" -> Controlled selection + Auto create
        if ($request->filled('tags_input')) {
            $tagNames = array_filter(array_map('trim', explode(',', $request->tags_input)));
            $tagIds = [];

            foreach ($tagNames as $name) {
                // Auto create jika tidak ada
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['nama_tag' => $name]
                );
                $tagIds[] = $tag->id;
            }

            $article->tags()->sync($tagIds);
        } else {
            $article->tags()->sync([]);
        }

        return back()->with('success', 'Artikel berhasil disimpan dengan status: ' . strtoupper($article->status));
    }

    /**
     * Preview Data (JSON output for Drawer/Alpine Modal)
     */
    public function apiDetail($id)
    {
        $article = Article::with(['category', 'tags', 'user'])->findOrFail($id);
        return response()->json($article);
    }

    /**
     * Aksi Massal (Bulk Action)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:draft,delete',
            'selected_ids' => 'required|string'
        ]);

        $ids = explode(',', $request->selected_ids);

        if ($request->action == 'delete') {
            Article::whereIn('id', $ids)->delete();
            return back()->with('success', "Berhasil menghapus " . count($ids) . " artikel sekaligus.");
        }

        if ($request->action == 'draft') {
            Article::whereIn('id', $ids)->update(['status' => 'draft']);
            return back()->with('success', "Berhasil mengubah " . count($ids) . " artikel menjadi Draft.");
        }
    }
}
