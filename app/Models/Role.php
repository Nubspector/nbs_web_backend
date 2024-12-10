<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    protected $primaryKey = 'id';
    public $timestamps = false; // Tidak menggunakan created_at dan updated_at

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = [
        'name',
    ];

    // Relasi dengan Employee
    public function employees()
    {
        return $this->hasMany(Employee::class, 'role_id');
    }
}
