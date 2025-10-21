<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupCatatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'grup_id',
        'user_id',
        'category_id',
        'custom_category',
        'deskripsi',
        'jumlah',
        'alokasi',
        // 'grup_rencana_id', // Aktifkan nanti
        'media',
    ];

    // Load relasi category secara default (mirip Catatan personal)
    protected $with = ['category', 'user']; // Tambahkan 'user' untuk tahu siapa pencatat

    /**
     * Relasi ke Grup.
     */
    public function grup(): BelongsTo
    {
        return $this->belongsTo(Grup::class);
    }

    /**
     * Relasi ke User (pencatat).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Rencana Grup (buat nanti).
     */
    // public function grupRencana(): BelongsTo
    // {
    //     return $this->belongsTo(GrupRencana::class);
    // }

    /**
     * Accessor untuk nama kategori (mirip Catatan personal).
     */
    public function getCategoryNameAttribute()
    {
        if ($this->custom_category) {
            return $this->custom_category;
        }
        return $this->category ? $this->category->nama : '-'; // Ubah 'name' jadi 'nama' jika kolomnya 'nama'
    }

     /**
     * Accessor untuk tipe kategori (pemasukan/pengeluaran).
     */
    public function getTipeAttribute()
    {
        return $this->category ? $this->category->tipe : null;
    }
}
