<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'data_nascimento',
        'password',
        'profile_photo',
        'professional_title',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'data_nascimento'   => 'date',
        ];
    }

    // Relação com portfolio
    public function portfolioItems()
    {
        return $this->hasMany(PortfolioItem::class);
    }
}
