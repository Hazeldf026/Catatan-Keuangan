<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsToMany(User::class, 'grup_user')
                    ->withPivot('role') // Ambil data role
                    ->withTimestamps();
    }

    /**
     * Mendapatkan user (pemilik) yang membuat grup ini.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function grupCatatans(): HasMany 
    {
        return $this->hasMany(GrupCatatan::class);
    }

    public function grupRencanas(): HasMany 
    {
        return $this->hasMany(GrupRencana::class);
    }

    public function getUserRole(User $user): ?string 
    {

        $anggota = $this->users()->find($user->id);
        return $anggota ? $anggota->pivot->role : null;
    }

    public function isUserAdmin(User $user): bool 
    {
        return $this->getUserRole($user) === 'admin';
    }
}
