<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratPermohonan extends Model
{
    protected $table = 'surat_permohonan';

    protected $fillable = [
        'nomor_tiket', 'penduduk_id', 'operator_id', 'kades_id',
        'jenis_surat', 'prioritas', 'status',
        'catatan', 'alasan_tolak',
        'keperluan', 'berlaku_hingga', 'nama_usaha',
        'lampiran_path', 'pdf_path',
        'tanggal_pengajuan', 'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'datetime',
            'tanggal_selesai'   => 'datetime',
        ];
    }

    // ─── Constants ─────────────────────────────────────────────

    public const SLA_HOURS = 24;

    public const JENIS_LABELS = [
        'sktm'            => 'Surat Keterangan Tidak Mampu (SKTM)',
        'domisili'        => 'Surat Keterangan Domisili',
        'pengantar_usaha' => 'Surat Pengantar Usaha',
        'kematian'        => 'Surat Keterangan Kematian',
        'pengantar_nikah' => 'Surat Pengantar Nikah',
        'pindah'          => 'Surat Keterangan Pindah',
    ];

    public const JENIS_SHORT = [
        'sktm'            => 'SKTM',
        'domisili'        => 'Surat Domisili',
        'pengantar_usaha' => 'Pengantar Usaha',
        'kematian'        => 'Surat Kematian',
        'pengantar_nikah' => 'Pengantar Nikah',
        'pindah'          => 'Surat Pindah',
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    // ─── Accessors ─────────────────────────────────────────────

    public function jenisLabel(): string
    {
        return self::JENIS_LABELS[$this->jenis_surat] ?? $this->jenis_surat;
    }

    public function jenisShort(): string
    {
        return self::JENIS_SHORT[$this->jenis_surat] ?? $this->jenis_surat;
    }

    public function statusBadge(): array
    {
        return match ($this->status) {
            'pengajuan'    => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'border' => 'border-blue-200',   'icon' => 'fa-file-import',  'label' => 'Pengajuan Baru'],
            'verifikasi'   => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'border' => 'border-amber-200',  'icon' => 'fa-list-check',   'label' => 'Verifikasi Berkas'],
            'menunggu_tte' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'icon' => 'fa-signature',    'label' => 'Menunggu TTE Kades'],
            'selesai'      => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'border' => 'border-green-200',  'icon' => 'fa-check-double', 'label' => 'Selesai'],
            'ditolak'      => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'border' => 'border-red-200',    'icon' => 'fa-xmark',        'label' => 'Ditolak'],
            default        => ['bg' => 'bg-gray-100',   'text' => 'text-gray-700',   'border' => 'border-gray-200',   'icon' => 'fa-file',         'label' => ucfirst($this->status)],
        };
    }

    public function prioritasBadge(): array
    {
        return match ($this->prioritas) {
            'tinggi' => ['bg' => 'bg-red-100',  'text' => 'text-red-700',  'border' => 'border-red-200',  'label' => 'Tinggi (Urgent)'],
            'normal' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Normal'],
        };
    }

    /**
     * SLA info: remaining hours or overdue.
     */
    public function slaInfo(): array
    {
        if (in_array($this->status, ['selesai', 'ditolak'])) {
            return ['label' => 'Selesai', 'overdue' => false, 'hours' => 0];
        }

        $deadline = $this->tanggal_pengajuan->addHours(self::SLA_HOURS);
        $now = Carbon::now();
        $diffHours = $now->diffInHours($deadline, false);

        if ($diffHours < 0) {
            return [
                'label'   => 'Terlambat (' . abs(round($diffHours)) . ' Jam)',
                'overdue' => true,
                'hours'   => abs(round($diffHours)),
            ];
        }

        return [
            'label'   => 'Sisa ' . round($diffHours) . ' Jam',
            'overdue' => false,
            'hours'   => round($diffHours),
        ];
    }

    /**
     * Safely format berlaku_hingga field.
     * Can be a date or a string like "1 Bulan".
     */
    public function formatBerlakuHingga(): string
    {
        if (!$this->berlaku_hingga) {
            return '-';
        }

        try {
            // Check if it's a valid date string (YYYY-MM-DD)
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->berlaku_hingga)) {
                return Carbon::parse($this->berlaku_hingga)->translatedFormat('d F Y');
            }
            
            // Try parsing anyway if it looks like a date
            return Carbon::parse($this->berlaku_hingga)->translatedFormat('d F Y');
        } catch (\Exception $e) {
            // If it fails (e.g. "1 Bulan"), return as is
            return $this->berlaku_hingga;
        }
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status', ['pengajuan', 'verifikasi', 'menunggu_tte']);
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Only archived letters (selesai + ditolak).
     */
    public function scopeArsip(Builder $query): Builder
    {
        return $query->whereIn('status', ['selesai', 'ditolak']);
    }

    /**
     * Search by ticket number or applicant name (for archive).
     */
    public function scopeSearchArsip(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;
        return $query->where(fn (Builder $q) =>
            $q->where('nomor_tiket', 'like', "%{$search}%")
              ->orWhereHas('penduduk', fn (Builder $pq) =>
                  $pq->where('nama', 'like', "%{$search}%")
                     ->orWhere('nik', 'like', "%{$search}%")
              )
        );
    }

    public function kades(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kades_id');
    }

    public function lampiran(): HasMany
    {
        return $this->hasMany(LampiranSurat::class);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->aktif()
            ->where('tanggal_pengajuan', '<', Carbon::now()->subHours(self::SLA_HOURS));
    }

    public function scopeFilterJenis(Builder $query, ?string $jenis): Builder
    {
        if (!$jenis) return $query;
        return $query->where('jenis_surat', $jenis);
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if (!$status) return $query;
        return $query->where('status', $status);
    }

    public function scopeFilterPeriode(Builder $query, ?string $periode): Builder
    {
        return match ($periode) {
            'hari'   => $query->whereDate('tanggal_pengajuan', Carbon::today()),
            'minggu' => $query->whereBetween('tanggal_pengajuan', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
            'bulan'  => $query->whereMonth('tanggal_pengajuan', Carbon::now()->month)
                              ->whereYear('tanggal_pengajuan', Carbon::now()->year),
            default  => $query,
        };
    }

    // ─── Auto Ticket Number ────────────────────────────────────

    public static function generateTiket(): string
    {
        $date = Carbon::now()->format('ymd');
        $last = static::where('nomor_tiket', 'like', "#TKT-{$date}-%")
            ->orderByDesc('id')
            ->value('nomor_tiket');

        $nextNum = 1;
        if ($last) {
            preg_match('/-(\d+)$/', $last, $matches);
            $nextNum = (int) ($matches[1] ?? 0) + 1;
        }

        return sprintf('#TKT-%s-%03d', $date, $nextNum);
    }
}
