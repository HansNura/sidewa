<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemConfigController extends Controller
{
    /**
     * Display the system configuration page (tabbed).
     */
    public function edit(): View
    {
        $config = SystemConfig::allKeyed();

        // Activity logs for the "Audit & System Log" tab
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(15);

        return view('pages.backoffice.system-config.edit', [
            'config'    => $config,
            'logs'      => $logs,
            'pageTitle' => 'Konfigurasi Sistem',
        ]);
    }

    /**
     * Save system configuration.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name'        => ['required', 'string', 'max:255'],
            'timezone'        => ['required', 'string', 'in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura'],
            'date_format'     => ['required', 'string', 'in:DD/MM/YYYY,DD MMMM YYYY,YYYY-MM-DD'],
            'language'        => ['required', 'string', 'in:id,en'],
            'password_policy' => ['required', 'string', 'in:low,medium,strong'],
            'session_timeout' => ['required', 'integer', 'min:5', 'max:1440'],
            'two_factor'      => ['sometimes', 'boolean'],
            'notif_internal'  => ['sometimes', 'boolean'],
            'notif_email'     => ['sometimes', 'boolean'],
            'notif_whatsapp'  => ['sometimes', 'boolean'],
            'wa_provider'     => ['nullable', 'string', 'in:fonnte,wablas,twilio'],
            'dukcapil_url'    => ['nullable', 'url', 'max:500'],
            'dukcapil_api_key'=> ['nullable', 'string', 'max:500'],
        ], [
            'app_name.required'        => 'Nama aplikasi wajib diisi.',
            'session_timeout.min'      => 'Batas waktu sesi minimal 5 menit.',
            'session_timeout.max'      => 'Batas waktu sesi maksimal 1440 menit (24 jam).',
        ]);

        // Checkboxes sent as "1" when checked, absent when unchecked
        $booleans = ['two_factor', 'notif_internal', 'notif_email', 'notif_whatsapp'];
        foreach ($booleans as $key) {
            $validated[$key] = $request->has($key);
        }

        // Save each config key
        $changed = [];
        foreach ($validated as $key => $value) {
            if (array_key_exists($key, SystemConfig::DEFAULTS)) {
                $old = SystemConfig::get($key);
                SystemConfig::set($key, $value);
                if ((string) $old !== (string) $value) {
                    $changed[$key] = ['from' => $old, 'to' => $value];
                }
            }
        }

        /** @var User $actor */
        $actor = $request->user();

        if (!empty($changed)) {
            ActivityLog::record(
                $actor,
                'update_system_config',
                'Memperbarui konfigurasi sistem (' . count($changed) . ' item diubah)',
                ['changes' => $changed]
            );
        }

        return redirect()
            ->route('admin.system-config.edit', ['tab' => $request->input('active_tab', 'umum')])
            ->with('success', 'Konfigurasi sistem berhasil disimpan.');
    }
}
