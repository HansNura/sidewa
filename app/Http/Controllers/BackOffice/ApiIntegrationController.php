<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiClient;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Hash;

class ApiIntegrationController extends Controller
{
    public function index()
    {
        $clients = ApiClient::orderBy('created_at', 'desc')->get();
        // limit 10 for display
        $logs = ApiLog::with('client')->orderBy('created_at', 'desc')->limit(10)->get();

        // Highcharts Data Preparation (dummy 24h fallback if empty, otherwise aggregation)
        // For simplicity, we just pass static data or simple aggregated
        $chartData = [
            'hours' => ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '23:59'],
            'traffic' => [120, 80, 850, 1200, 980, 450, 100],
            'latency' => [120, 110, 180, 240, 190, 130, 125],
        ];

        // Overall stats
        $totalRequests = ApiLog::count();
        if ($totalRequests == 0) $totalRequests = 12450; // Mock if empty

        $avgLatency = ApiLog::avg('latency_ms') ?? 145;

        return view('pages.backoffice.api-integration.index', compact('clients', 'logs', 'chartData', 'totalRequests', 'avgLatency'));
    }

    public function generateKey(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'scopes' => 'array'
        ]);

        $plainToken = ApiClient::generateToken();
        
        ApiClient::create([
            'name' => $request->name,
            'api_key' => Hash::make($plainToken),
            'plain_token_suffix' => substr($plainToken, 0, 8) . '...' . substr($plainToken, -4),
            'scopes' => $request->scopes ?? ['read'],
            'status' => 'active'
        ]);

        return back()->with('success', 'API Key berhasil digenerate: ' . $plainToken . ' (Simpan baik-baik karena token ini tidak akan ditampilkan lagi)');
    }

    public function revokeKey($id)
    {
        $client = ApiClient::findOrFail($id);
        $client->update(['status' => 'revoked']);

        return back()->with('success', "Akses API untuk klient $client->name telah dicabut.");
    }

    public function syncData(Request $request)
    {
        // Simulasi logika sync yang menerima Module & Direction & Cron
        
        // Return session syncStatus triggering Alpine processing
        return back()->with('syncProcessing', true);
    }
}
