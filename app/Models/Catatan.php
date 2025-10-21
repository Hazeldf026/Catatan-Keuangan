<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Catatan extends Model
{
        use HasFactory;

    protected $fillable = [
        'user_id', 
        'category_id', 
        'alokasi',
        'rencana_id',
        'media',
        'custom_category', 
        'deskripsi', 
        'jumlah'
    ];
    protected $with = ['category'];

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rencana(): BelongsTo
    {
        return $this->belongsTo(Rencana::class);
    }

    public function getCategoryNameAttribute()
    {
        if ($this->custom_category) {
            return $this->custom_category;
        }
        
        return $this->category ? $this->category->name : '-';
    }
}
