<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Grup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'deskripsi',
        'grup_code',
    ];

    /**
     * Mendapatkan user (anggota) yang ada di grup ini.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'grup_user');
    }

    /**
     * Mendapatkan user (pemilik) yang membuat grup ini.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
