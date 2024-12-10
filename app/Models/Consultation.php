<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $table = 'consultation';
    protected $primaryKey = 'id';
    public $timestamps = true; // Menggunakan created_at dan updated_at otomatis

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
        'username',
        'password',
        'created',
    ];
    // Menyembunyikan kolom yang tidak ingin terlihat ketika serialized ke JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //Format casting untuk atribut
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke user yang membuat produk (assumed)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'doctor_id');
    }
}
