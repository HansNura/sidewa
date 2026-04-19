<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = [
        'tipe',
        'nama',
        'parent_id',
        'kepala_nama',
        'kepala_jabatan',
        'kepala_telepon',
        'geojson',
    ];

    protected function casts(): array
    {
        return [
            'geojson' => 'array',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'parent_id')->orderBy('nama');
    }

    /**
     * Recursively load the full subtree.
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    // ─── Accessors ─────────────────────────────────────────────

    /**
     * Display label (e.g. "Dusun Kaler", "RW 01", "RT 03").
     */
    public function label(): string
    {
        return match ($this->tipe) {
            'dusun' => "Dusun {$this->nama}",
            'rw'    => "RW {$this->nama}",
            'rt'    => "RT {$this->nama}",
        };
    }

    /**
     * Parent path for breadcrumb display.
     */
    public function parentPath(): string
    {
        if (!$this->parent_id) return '- (Root)';

        $parts = [];
        $current = $this->parent;
        while ($current) {
            $parts[] = $current->label();
            $current = $current->parent;
        }
        return implode(' / ', array_reverse($parts));
    }

    /**
     * Tipe badge color map.
     */
    public function tipeBadge(): array
    {
        return match ($this->tipe) {
            'dusun' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
            'rw'    => ['bg' => 'bg-blue-100',  'text' => 'text-blue-800',  'border' => 'border-blue-200'],
            'rt'    => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600',  'border' => 'border-gray-200'],
        };
    }

    /**
     * Get all descendant wilayah IDs (including self).
     */
    public function descendantIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->descendantIds());
        }
        return $ids;
    }

    /**
     * Population count from linked penduduk (via dusun + rt + rw matching).
     * Uses the proper hierarchy: count penduduk in this wilayah and all children.
     */
    public function populasi(): int
    {
        return $this->pendudukQuery()->count();
    }

    /**
     * KK count from linked penduduk.
     */
    public function jumlahKK(): int
    {
        return $this->pendudukQuery()->distinct('no_kk')->count('no_kk');
    }

    /**
     * Build a query for penduduk belonging to this wilayah (and descendants).
     */
    public function pendudukQuery(): Builder
    {
        // Collect all dusun/rt/rw values from this level and below
        if ($this->tipe === 'dusun') {
            return Penduduk::where('dusun', $this->nama)->where('status', 'hidup');
        }

        if ($this->tipe === 'rw') {
            $dusun = $this->parent?->nama;
            return Penduduk::where('dusun', $dusun)
                ->where('rw', $this->nama)
                ->where('status', 'hidup');
        }

        // RT level
        $rw = $this->parent;
        $dusun = $rw?->parent?->nama;
        return Penduduk::where('dusun', $dusun)
            ->where('rw', $rw?->nama)
            ->where('rt', $this->nama)
            ->where('status', 'hidup');
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where('nama', 'like', "%{$search}%")
            ->orWhere('kepala_nama', 'like', "%{$search}%");
    }

    public function scopeDusun(Builder $query): Builder
    {
        return $query->where('tipe', 'dusun');
    }

    /**
     * Get the full tree from root for the tree view.
     */
    public static function tree(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('tipe', 'dusun')
            ->with('childrenRecursive')
            ->orderBy('nama')
            ->get();
    }

    /**
     * Flattened list for the table view (sorted by hierarchy).
     */
    public static function flatList(?string $search = null): array
    {
        $tree = static::tree();
        $flat = [];

        foreach ($tree as $dusun) {
            if ($search && !str_contains(strtolower($dusun->nama), strtolower($search))
                && !str_contains(strtolower($dusun->kepala_nama ?? ''), strtolower($search))) {
                // Check children too
                $hasMatch = false;
                foreach ($dusun->childrenRecursive as $rw) {
                    if (str_contains(strtolower($rw->nama), strtolower($search))) { $hasMatch = true; break; }
                    foreach ($rw->childrenRecursive ?? [] as $rt) {
                        if (str_contains(strtolower($rt->nama), strtolower($search))) { $hasMatch = true; break 2; }
                    }
                }
                if (!$hasMatch) continue;
            }

            $flat[] = ['wilayah' => $dusun, 'depth' => 0];
            foreach ($dusun->childrenRecursive as $rw) {
                $flat[] = ['wilayah' => $rw, 'depth' => 1];
                foreach ($rw->childrenRecursive ?? [] as $rt) {
                    $flat[] = ['wilayah' => $rt, 'depth' => 2];
                }
            }
        }

        return $flat;
    }
}
