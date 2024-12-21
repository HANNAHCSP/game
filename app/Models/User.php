<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'user_data', 'score', 'credit_card'
    ];
    
    protected $hidden = [
        'password'
    ];
    
    protected $casts = [
        'user_data' => 'array',
        'credit_card' => 'array'
    ];
}