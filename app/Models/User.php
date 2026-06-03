<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;           // ← add this

// User model - represents all users who can access the system
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;    // ginagamit for authentication & notification features ng system
    protected $fillable = [       // mga user details na pwedeng isave o iupdate sa database
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [        //di dapat makita
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool   // helper method para madaling malaman kung admin ang user
    {
        return $this->role === 'admin';
    }

    public function stockMovements()  // relationship ng user sa stock movements na kanyang ginawa
    {
        return $this->hasMany(StockMovement::class);
    }
}