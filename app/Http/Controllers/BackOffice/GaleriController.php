<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\AlbumGaleri;
use App\Models\MediaGaleri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    /**
     * Menampilkan Dasbor Galeri Media & Album
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'media');
        
        // Metrik Dashboard Summary
        $totalMedia = MediaGaleri::count();
        $totalVideo = MediaGaleri::where('file_type', 'video')->count();
        $totalAlbum = AlbumGaleri::count();
        $totalSizeBytes = MediaGaleri::sum('file_size'); // in bytes

        // Format Storage Used (e.g. 1.2 GB)
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($totalSizeBytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $formattedStorageVal = round($bytes / pow(1024, $pow), 2);
        $formattedStorageUnit = $units[$pow];

        // Fetch Data berdasarkan Tab
        $albums = AlbumGaleri::withCount('medias')->orderBy('created_at', 'desc')->get();
        
        $mediaQuery = MediaGaleri::with('album')->orderBy('created_at', 'desc');
        if ($request->album_id) {
            $mediaQuery->where('album_id', $request->album_id);
        }
        $medias = $mediaQuery->paginate(24);

        return view('pages.backoffice.galeri.index', compact(
            'tab', 'totalMedia', 'totalVideo', 'totalAlbum', 
            'formattedStorageVal', 'formattedStorageUnit', 
            'albums', 'medias'
        ));
    }

    /**
     * Simpan Album Baru
     */
    public function storeAlbum(Request $request)
    {
        $request->validate([
            'nama_album' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            // cover_image akan diimplementasi dinamis kedepan
        ]);

        AlbumGaleri::create([
            'nama_album' => $request->nama_album,
            'deskripsi' => $request->deskripsi
        ]);

        return back()->with('success', 'Album baru berhasil dibuat.');
    }

    /**
     * Upload Media (Bulk Allowed by HTML Input structure - implemented iteratively here)
     */
    public function storeMedia(Request $request)
    {
        // Validasi disesuaikan dengan permintaan: Image max 5MB, Video Max 20MB
        // Karena input files bisa campuran, kita validasi per mime
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,mp4|max:20480', // limit total tertinggi dulu
            'album_id' => 'nullable|exists:album_galeris,id',
            'deskripsi' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $uploadedCount = 0;

        foreach ($request->file('files') as $file) {
            // Check spesifik: Jika gambar max 5MB, jika video max 20MB
            $sizeKB = $file->getSize() / 1024;
            $extension = strtolower($file->getClientOriginalExtension());
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
            $isVideo = $extension === 'mp4';

            if ($isImage && $sizeKB > 5120) { // 5MB limit manual fallback
                return back()->with('error', 'Salah satu gambar melebihi batas 5MB.');
            }
            if ($isVideo && $sizeKB > 20480) { // 20MB limit manual fallback
                return back()->with('error', 'Salah satu video melebihi batas 20MB.');
            }

            $type = $isVideo ? 'video' : 'image';
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
            $path = $file->storeAs('galeri', $fileName, 'public');

            MediaGaleri::create([
                'album_id' => $request->album_id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $type,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'deskripsi' => $request->deskripsi,
                'tags' => $request->tags,
                'uploader_name' => auth()->user()->name ?? 'Administrator',
            ]);

            $uploadedCount++;
        }

        return back()->with('success', "$uploadedCount file media berhasil diunggah.");
    }

    /**
     * Fetch Media Drawer Detail (AJAX)
     */
    public function detailMedia($id)
    {
        $media = MediaGaleri::with('album')->findOrFail($id);
        return view('pages.backoffice.galeri._drawer_detail_content', compact('media'));
    }

    /**
     * Hapus Media
     */
    public function destroyMedia($id)
    {
        $media = MediaGaleri::findOrFail($id);
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return back()->with('success', 'Media berhasil dihapus permanen.');
    }

    /**
     * Hapus Album
     */
    public function destroyAlbum($id)
    {
        $album = AlbumGaleri::findOrFail($id);
        
        // Kosongkan referensi media
        MediaGaleri::where('album_id', $album->id)->update(['album_id' => null]);
        $album->delete();

        return back()->with('success', 'Album berhasil dihapus. Media di dalamnya tidak terhapus (Menjadi Tanpa Album).');
    }
}
