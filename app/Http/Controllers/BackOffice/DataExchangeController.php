<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataExchangeLog;
use App\Models\Warga;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DataExchangeController extends Controller
{
    public function index(Request $request)
    {
        $logs = DataExchangeLog::with('user')->orderBy('created_at', 'desc')->paginate(15);
        $activeTab = session('activeTab', 'export');
        $importStatusMsg = session('importStatusMsg', null);

        return view('pages.backoffice.data-exchange.index', compact('logs', 'activeTab', 'importStatusMsg'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'module' => 'required',
            'format' => 'required|in:excel,csv,json',
            // 'fields' => 'array', // For future complex dynamic fields
        ]);

        $module = $request->input('module');
        $format = $request->input('format'); // excel, csv, json

        $query = null;
        $exportFileName = "Export_{$module}_" . date('Y_m_d_His');
        $dataRows = [];

        // Simple Implementation for Warga / Penduduk
        if ($module == 'penduduk') {
            $query = Warga::query();
            // Optional: apply filters here if provided in request
            $dataRows = $query->get()->map(function($w) {
                return [
                    'NIK' => $w->nik,
                    'No_KK' => $w->no_kk,
                    'Nama_Lengkap' => $w->nama_lengkap,
                    'Jenis_Kelamin' => $w->jenis_kelamin,
                    'Tempat_Lahir' => $w->tempat_lahir,
                    'Tanggal_Lahir' => $w->tanggal_lahir,
                    'Pendidikan' => $w->pendidikan_terakhir,
                    'Pekerjaan' => $w->pekerjaan,
                ];
            })->toArray();
        } else {
            // Placeholder for other modules
            return back()->with('error', 'Modul tidak didukung saat ini.');
        }

        // Logging
        DataExchangeLog::create([
            'tipe' => 'export',
            'modul_tujuan' => $module,
            'nama_file' => "$exportFileName.$format",
            'status' => 'success',
            'jumlah_berhasil' => count($dataRows),
            'user_id' => Auth::id()
        ]);

        if ($format == 'json') {
            return response()->json($dataRows);
        }

        $extension = $format === 'excel' ? 'xlsx' : 'csv';
        
        return response()->streamDownload(function () use ($dataRows, $extension) {
            $writer = SimpleExcelWriter::streamDownload('temp.'.$extension, $extension);
            foreach ($dataRows as $row) {
                $writer->addRow($row);
            }
            $writer->close();
        }, "$exportFileName.$extension");
    }

    public function downloadTemplate(Request $request)
    {
        $module = $request->get('module', 'penduduk');
        $fileName = "Template_Import_{$module}.xlsx";
        
        return response()->streamDownload(function () {
            $writer = SimpleExcelWriter::streamDownload('temp.xlsx', 'xlsx');
            $writer->addRow([
                'NIK' => '320912340001',
                'No_KK' => '320912340000',
                'Nama_Lengkap' => 'Cth: Budi Santoso',
                'Jenis_Kelamin' => 'L/P',
                'Tempat_Lahir' => 'Ciamis',
                'Tanggal_Lahir' => '1980-01-01',
            ]);
            $writer->close();
        }, $fileName);
    }

    public function importExecute(Request $request)
    {
        $request->validate([
            'module' => 'required',
            'file' => 'required|file|mimes:csv,xlsx,xls|max:50000',
        ]);

        $file = $request->file('file');
        $module = $request->input('module');
        $path = $file->getRealPath();

        $reader = SimpleExcelReader::create($path);
        
        $successCount = 0;
        $failCount = 0;
        $errorNotes = [];

        if ($module == 'penduduk') {
            $reader->getRows()->each(function(array $rowProperties) use (&$successCount, &$failCount, &$errorNotes) {
                try {
                    // Cek duplikat
                    $nik = $rowProperties['NIK'] ?? null;
                    if (!$nik) {
                        $failCount++;
                        return; // Skip if no NIK
                    }

                    if (Warga::where('nik', $nik)->exists()) {
                        $failCount++;
                        $errorNotes[] = "Duplikat NIK: $nik";
                        return;
                    }

                    Warga::create([
                        'nik' => $nik,
                        'no_kk' => $rowProperties['No_KK'] ?? '00000',
                        'nama_lengkap' => $rowProperties['Nama_Lengkap'] ?? 'Tanpa Nama',
                        'jenis_kelamin' => $rowProperties['Jenis_Kelamin'] ?? 'L',
                        'tempat_lahir' => $rowProperties['Tempat_Lahir'] ?? 'Lainnya',
                        'tanggal_lahir' => $rowProperties['Tanggal_Lahir'] ?? '1970-01-01',
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                    $errorNotes[] = "Error baris: " . ($nik ?? 'unknown');
                }
            });
        }

        DataExchangeLog::create([
            'tipe' => 'import',
            'modul_tujuan' => $module,
            'nama_file' => $file->getClientOriginalName(),
            'status' => $failCount == 0 ? 'success' : 'partial',
            'jumlah_berhasil' => $successCount,
            'jumlah_gagal' => $failCount,
            'catatan_error' => implode(', ', array_slice($errorNotes, 0, 5)), // log up to 5 errors
            'user_id' => Auth::id()
        ]);

        return redirect()->route('admin.data-exchange.index')->with([
            'activeTab' => 'import',
            'importStatusMsg' => [
                'success' => $successCount,
                'failed' => $failCount,
                'errors' => array_slice($errorNotes, 0, 3)
            ]
        ]);
    }
}
