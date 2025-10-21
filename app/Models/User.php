<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'verification_code',
        'verification_code_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function catatan(): HasMany
    {
        return $this->hasMany(Catatan::class);
    }

    public function rencanas(): HasMany
    {
        return $this->hasMany(Rencana::class);
    }

    public function grups(): BelongsToMany
    {
        return $this->belongsToMany(Grup::class, 'grup_user')
                    ->withPivot('role') // Ambil data role
                    ->withTimestamps();
    }
    
    public function ownedGrups(): HasMany
    {
        return $this->hasMany(Grup::class, 'user_id');
    }

    public function grupCatatans(): HasMany
    {
        return $this->hasMany(GrupCatatan::class);
    }

    public function grupRencanas(): HasMany 
    {
        return $this->hasMany(GrupRencana::class);
    }

    public function getRoleInGroup(Grup $grup): ?string 
    {
        $keanggotaan = $this->grups()->find($grup->id);
        return $keanggotaan ? $keanggotaan->pivot->role : null;
    }

    public function isAdminInGroup(Grup $grup): bool 
    {
        return $this->getRoleInGroup($grup) === 'admin';
    }
}
