<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rencana extends Model
{
    /** @use HasFactory<\Database\Factories\RencanaFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'deskripsi',
        'target_jumlah',
        'jumlah_terkumpul',
        'target_tanggal',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function catatans(): HasMany
    {
        return $this->hasMany(Catatan::class);
    }
}
