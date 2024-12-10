<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promo';
    protected $primaryKey = 'id';
    public $timestamps = true; // Menggunakan created_at dan updated_at otomatis

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = [
        'name',
        'description',
        'discount',
        'additional_points',
        'start_date',
        'end_date',
        'promo_date',
        'is_active',
    ];

    // Casting untuk atribut
    protected $casts = [
        'discount' => 'decimal:2',  // Diskon dengan 2 desimal
        'additional_points' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'promo_date' => 'date',
        'is_active' => 'boolean',
    ];
}
