<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Catatan extends Model
{
        use HasFactory;

    protected $fillable = ['category_id', 'custom_category', 'deskripsi', 'jumlah',];

    protected $with = ['category'];

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCategoryNameAttribute()
    {
        if ($this->custom_category) {
            return $this->custom_category;
        }
        
        return $this->category ? $this->category->name : '-';
    }
}
