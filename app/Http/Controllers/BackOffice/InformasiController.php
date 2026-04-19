<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\InformasiPublik;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InformasiController extends Controller
{
    /**
     * Tampilan Dasar
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'list');
        $monthParam = $request->input('month', Carbon::now()->format('Y-m'));

        // Stats Filter
        $totalData = InformasiPublik::count();
        $totalActive = InformasiPublik::active()->count();
        $agendaTerdekat = InformasiPublik::agenda()->active()->where('start_date', '>=', Carbon::now())->count();
        $totalArchive = InformasiPublik::where('status', InformasiPublik::STATUS_ARCHIVED)
            ->orWhere(function($q) {
                $q->pengumuman()->where('end_date', '<', Carbon::now());
            })->count();

        // 1. LIST VIEW DATA
        $query = InformasiPublik::orderBy('created_at', 'desc');
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->active();
            } elseif ($request->status == 'expired') {
                $query->where(function($q) {
                    $q->where('status', InformasiPublik::STATUS_ARCHIVED)
                      ->orWhere('end_date', '<', Carbon::now());
                });
            } elseif ($request->status == 'draft') {
                $query->where('status', InformasiPublik::STATUS_DRAFT);
            }
        }
        
        $informasi = $query->paginate(15)->withQueryString();

        // 2. CALENDAR VIEW DATA
        $calendarDate = Carbon::parse($monthParam . '-01');
        $startOfCalendar = $calendarDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $calendarDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        
        $calendarItemsQuery = InformasiPublik::active()
            ->whereBetween('start_date', [$startOfCalendar, $endOfCalendar])
            ->get()
            ->groupBy(function($item) {
                return $item->start_date->format('Y-m-d');
            });

        // Generate full calendar grid (42 cells max)
        $calendarGrid = [];
        $currentDate = $startOfCalendar->copy();
        
        while ($currentDate <= $endOfCalendar) {
            $dateString = $currentDate->format('Y-m-d');
            $calendarGrid[] = [
                'date' => $currentDate->copy(),
                'day' => $currentDate->format('j'),
                'is_current_month' => $currentDate->format('Y-m') === $calendarDate->format('Y-m'),
                'is_today' => $currentDate->isToday(),
                'items' => $calendarItemsQuery->get($dateString, collect())
            ];
            $currentDate->addDay();
        }

        return view('pages.backoffice.informasi.index', compact(
            'tab', 'totalData', 'totalActive', 'agendaTerdekat', 'totalArchive', 
            'informasi', 'calendarDate', 'calendarGrid'
        ));
    }

    /**
     * Store atau Update Data
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pengumuman,agenda',
            'title' => 'required|string|max:255',
            'status' => 'required|in:publish,draft,archived',
            'content_html' => 'nullable|string',
            'start_date' => 'required|date',
        ]);

        // Conditional validation
        if ($request->type == InformasiPublik::TYPE_AGENDA) {
            $request->validate([
                'location' => 'required|string|max:255',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);
        } else {
            $request->validate([
                'end_date' => 'required|date|after:start_date',
            ]);
        }

        // Processing
        $info = $request->filled('id') ? InformasiPublik::findOrFail($request->id) : new InformasiPublik();
        
        $info->type = $request->type;
        $info->title = $request->title;
        $info->content_html = $request->content_html;
        $info->start_date = $request->start_date;
        $info->end_date = $request->end_date;
        $info->location = $request->location;
        $info->status = $request->status;

        if (!$request->filled('id')) {
            $info->slug = Str::slug($request->title) . '-' . mt_rand(1000, 9999);
        }

        $info->save();

        return back()->with('success', 'Informasi berhasil disimpan.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:archive,delete',
            'selected_ids' => 'required|string'
        ]);

        $ids = explode(',', $request->selected_ids);

        if ($request->action == 'delete') {
            InformasiPublik::whereIn('id', $ids)->delete();
            return back()->with('success', "Berhasil menghapus informasi terpilih.");
        }

        if ($request->action == 'archive') {
            InformasiPublik::whereIn('id', $ids)->update(['status' => InformasiPublik::STATUS_ARCHIVED]);
            return back()->with('success', "Berhasil memperbarui status arsip.");
        }
    }

    public function apiDetail($id)
    {
        return response()->json(InformasiPublik::findOrFail($id));
    }
}
