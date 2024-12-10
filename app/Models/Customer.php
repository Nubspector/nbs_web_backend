<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'customer';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'date_of_birth',
        'gender',
        'address',
        'phone_number',
        'email',
        'username',
        'password',
        'allergy',
        'points',
        'points_used',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];
}
