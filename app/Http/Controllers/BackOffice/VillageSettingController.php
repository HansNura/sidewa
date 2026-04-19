<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\VillageSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VillageSettingController extends Controller
{
    /**
     * Display the village identity settings form.
     */
    public function edit(): View
    {
        $settings = VillageSetting::instance();

        return view('pages.backoffice.village-settings.edit', [
            'settings'  => $settings,
            'pageTitle' => 'Pengaturan Identitas Desa',
        ]);
    }

    /**
     * Update village identity settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_desa'     => ['required', 'string', 'max:255'],
            'kecamatan'     => ['required', 'string', 'max:255'],
            'kabupaten'     => ['required', 'string', 'max:255'],
            'provinsi'      => ['required', 'string', 'max:255'],
            'kode_pos'      => ['required', 'string', 'max:10'],
            'alamat'        => ['required', 'string', 'max:1000'],
            'email'         => ['nullable', 'email', 'max:255'],
            'telepon'       => ['nullable', 'string', 'max:50'],
            'website'       => ['nullable', 'string', 'max:255'],
            'nama_kades'    => ['nullable', 'string', 'max:255'],
            'nip_kades'     => ['nullable', 'string', 'max:100'],
            'logo'          => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'banner'        => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ], [
            'nama_desa.required' => 'Nama desa wajib diisi.',
            'kecamatan.required' => 'Kecamatan wajib diisi.',
            'kabupaten.required' => 'Kabupaten wajib diisi.',
            'provinsi.required'  => 'Provinsi wajib diisi.',
            'kode_pos.required'  => 'Kode pos wajib diisi.',
            'alamat.required'    => 'Alamat kantor wajib diisi.',
            'logo.image'         => 'File logo harus berupa gambar.',
            'logo.max'           => 'Ukuran logo maksimal 2MB.',
            'banner.image'       => 'File banner harus berupa gambar.',
            'banner.max'         => 'Ukuran banner maksimal 5MB.',
        ]);

        $settings = VillageSetting::instance();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('village/logo', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($settings->banner_path) {
                Storage::disk('public')->delete($settings->banner_path);
            }
            $validated['banner_path'] = $request->file('banner')->store('village/banner', 'public');
        }

        // Remove file inputs from validated data (we use _path instead)
        unset($validated['logo'], $validated['banner']);

        $settings->update($validated);

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'update_village_settings',
            'Memperbarui identitas desa',
            ['fields_updated' => array_keys($validated)]
        );

        return redirect()
            ->route('admin.village-settings.edit')
            ->with('success', 'Identitas desa berhasil diperbarui.');
    }
}
