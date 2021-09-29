<?php

namespace Justijndepover\Settings\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Justijndepover\Settings\Concerns\HasSettings;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasSettings;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

