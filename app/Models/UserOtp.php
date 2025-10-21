<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOtp extends Model
{
    use HasFactory;

    // Sesuaikan nama tabel jika berbeda
    protected $table = 'user_otps';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
        'type', // Hapus jika tidak ada kolom type
    ];

    // Casts tipe data
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}