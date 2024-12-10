<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $table = 'treatments';
    protected $primaryKey = 'id';
    public $timestamps = true; // Menggunakan created_at dan updated_at otomatis

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = [
        'name',
        'image',
        'type',
        'description',
        'price',
        'point_discount',
        'created_by',
    ];

    // Casting untuk atribut
    protected $casts = [
        'type' => 'string',
        'price' => 'decimal:2', // Harga dengan 2 desimal
        'point_discount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke user yang membuat treatment (assumed)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by'); // Relasi ke user yang membuat treatment
    }
}
