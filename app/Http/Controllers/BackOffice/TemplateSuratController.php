<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\TemplateSurat;
use App\Models\User;
use App\Models\VillageSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateSuratController extends Controller
{
    /**
     * Template list page with search/filter.
     */
    public function index(Request $request): View
    {
        $templates = TemplateSurat::with('editor')
            ->search($request->input('search'))
            ->filterKategori($request->input('kategori'))
            ->filterStatus($request->input('status'))
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('pages.backoffice.template-surat.index', [
            'pageTitle'  => 'Template Surat',
            'templates'  => $templates,
            'kategoris'  => TemplateSurat::KATEGORI_LABELS,
            'search'     => $request->input('search'),
            'selKategori' => $request->input('kategori'),
            'selStatus'  => $request->input('status'),
        ]);
    }

    /**
     * Show the full-screen editor for a new template.
     */
    public function create(): View
    {
        $village = VillageSetting::instance();

        return view('pages.backoffice.template-surat.editor', [
            'pageTitle'    => 'Buat Template Baru',
            'template'     => null,
            'village'      => $village,
            'kategoris'    => TemplateSurat::KATEGORI_LABELS,
            'systemFields' => TemplateSurat::SYSTEM_FIELDS,
            'manualFields' => TemplateSurat::MANUAL_FIELDS,
        ]);
    }

    /**
     * Show the full-screen editor for existing template.
     */
    public function edit(TemplateSurat $template): View
    {
        $village = VillageSetting::instance();

        return view('pages.backoffice.template-surat.editor', [
            'pageTitle'    => 'Edit Template: ' . $template->nama,
            'template'     => $template,
            'village'      => $village,
            'kategoris'    => TemplateSurat::KATEGORI_LABELS,
            'systemFields' => TemplateSurat::SYSTEM_FIELDS,
            'manualFields' => TemplateSurat::MANUAL_FIELDS,
        ]);
    }

    /**
     * Store a new template.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'kode'            => ['required', 'string', 'max:50', 'unique:template_surat,kode'],
            'kategori'        => ['required', 'in:keterangan,pengantar,rekomendasi'],
            'deskripsi'       => ['nullable', 'string', 'max:500'],
            'body_template'   => ['required', 'string'],
            'layout_settings' => ['nullable', 'array'],
            'is_active'       => ['boolean'],
        ], [
            'nama.required'          => 'Nama template wajib diisi.',
            'kode.required'          => 'Kode template wajib diisi.',
            'kode.unique'            => 'Kode template sudah digunakan.',
            'body_template.required' => 'Isi template (body) wajib diisi.',
        ]);

        $validated['versi']      = 'v1.0';
        $validated['updated_by'] = $request->user()->id;

        $template = TemplateSurat::create($validated);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'buat_template', "Membuat template surat: {$template->nama} (v1.0)");

        return response()->json([
            'success' => true,
            'message' => "Template \"{$template->nama}\" berhasil dibuat.",
            'id'      => $template->id,
        ]);
    }

    /**
     * Update existing template.
     */
    public function update(Request $request, TemplateSurat $template): JsonResponse
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'kode'            => ['required', 'string', 'max:50', "unique:template_surat,kode,{$template->id}"],
            'kategori'        => ['required', 'in:keterangan,pengantar,rekomendasi'],
            'deskripsi'       => ['nullable', 'string', 'max:500'],
            'body_template'   => ['required', 'string'],
            'layout_settings' => ['nullable', 'array'],
            'is_active'       => ['boolean'],
        ]);

        // Auto-increment version
        $currentVersion = $template->versi;
        preg_match('/v(\d+)\.(\d+)/', $currentVersion, $m);
        $major = (int) ($m[1] ?? 1);
        $minor = (int) ($m[2] ?? 0) + 1;
        $newVersion = "v{$major}.{$minor}";

        $validated['versi']      = $newVersion;
        $validated['updated_by'] = $request->user()->id;

        $template->update($validated);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'update_template', "Memperbarui template: {$template->nama} ({$newVersion})");

        return response()->json([
            'success' => true,
            'message' => "Template \"{$template->nama}\" diperbarui ke {$newVersion}.",
            'version' => $newVersion,
        ]);
    }

    /**
     * Toggle template active/inactive status (AJAX).
     */
    public function toggleStatus(Request $request, TemplateSurat $template): JsonResponse
    {
        $template->is_active = !$template->is_active;
        $template->updated_by = $request->user()->id;
        $template->save();

        $statusLabel = $template->is_active ? 'Aktif' : 'Nonaktif';

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'toggle_template', "Mengubah status template {$template->nama} → {$statusLabel}");

        return response()->json([
            'success'   => true,
            'is_active' => $template->is_active,
            'message'   => "Status template diubah ke: {$statusLabel}",
        ]);
    }

    /**
     * Get template data for preview (JSON).
     */
    public function show(TemplateSurat $template): JsonResponse
    {
        return response()->json([
            'id'             => $template->id,
            'nama'           => $template->nama,
            'kode'           => $template->kode,
            'kategori'       => $template->kategoriLabel(),
            'body_template'  => $template->body_template,
            'field_count'    => $template->fieldCount(),
            'used_fields'    => $template->usedFields(),
            'layout'         => $template->resolvedLayout(),
            'versi'          => $template->versi,
            'editor_name'    => $template->editor?->name ?? '-',
            'updated_at'     => $template->updated_at->translatedFormat('d M Y, H:i'),
        ]);
    }

    /**
     * Delete template.
     */
    public function destroy(Request $request, TemplateSurat $template): RedirectResponse
    {
        $nama = $template->nama;
        $template->delete();

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'hapus_template', "Menghapus template surat: {$nama}");

        return redirect()
            ->route('admin.template-surat.index')
            ->with('success', "Template \"{$nama}\" berhasil dihapus.");
    }
}
