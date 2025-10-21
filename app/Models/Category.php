<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = ['nama', 'tipe'];

    public function catatan(): HasMany
    {
        return $this->hasMany(Catatan::class);
    }

    public function grupCatatans(): HasMany
    {
        return $this->hasMany(GrupCatatan::class);
    }
}
