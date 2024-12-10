<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'id';
    public $timestamps = true; // Menggunakan created_at dan updated_at otomatis

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = [
        'name',
        'category',
        'image',
        'price',
        'description',
        'weight',
        'unit',
        'stock',
        'created_by',
    ];

    // Menyembunyikan kolom yang tidak ingin terlihat ketika serialized ke JSON
    protected $hidden = [
        'created_by',
    ];

    // Format casting untuk atribut
    protected $casts = [
        'price' => 'decimal:2',   // Harga dengan 2 desimal
        'weight' => 'decimal:2',  // Berat dengan 2 desimal
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke user yang membuat produk (assumed)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by'); // Relasi ke tabel user dengan foreign key created_by
    }
}
