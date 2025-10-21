<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupRencana extends Model
{
    use HasFactory;

    protected $fillable = [
        'grup_id',
        'user_id', // User yang membuat
        'nama',
        'deskripsi',
        'target_jumlah',
        'target_tanggal',
        'jumlah_terkumpul',
        'status',
        'is_pinned',
    ];

    // Casts untuk tipe data
    protected $casts = [
        'target_jumlah' => 'decimal:2',
        'jumlah_terkumpul' => 'decimal:2',
        'target_tanggal' => 'date',
        'is_pinned' => 'boolean',
    ];

    // Relasi
    public function grup(): BelongsTo {
        return $this->belongsTo(Grup::class);
    }

    public function user(): BelongsTo { // User pembuat
        return $this->belongsTo(User::class);
    }

    // Accessor untuk progress (jika diperlukan)
    public function getProgressAttribute() {
        if ($this->target_jumlah <= 0) return 0;
        return min(100, ($this->jumlah_terkumpul / $this->target_jumlah) * 100);
    }
}
