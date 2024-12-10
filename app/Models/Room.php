<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room';
    protected $primaryKey = 'id';
    public $timestamps = true; // Menggunakan created_at dan updated_at otomatis

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = [
        'name',
        'status',
        'type',
    ];

    // Casting untuk atribut
    protected $casts = [
        'status' => 'string', // Menyimpan enum status sebagai string
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
