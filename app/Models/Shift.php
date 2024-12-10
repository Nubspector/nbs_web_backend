<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shift';
    protected $primaryKey = 'id';
    public $timestamps = false; // Tidak menggunakan created_at dan updated_at

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = [
        'day',
        'time_slot',
        'start_time',
        'end_time',
    ];

    // Casting untuk atribut
    protected $casts = [
        'day' => 'string', // Menyimpan enum hari sebagai string
        'time_slot' => 'string', // Menyimpan enum slot waktu sebagai string
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];
}
