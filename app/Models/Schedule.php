<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule';
    protected $primaryKey = 'id';
    public $timestamps = true; // Menggunakan created_at dan updated_at otomatis

    // Kolom yang bisa diisi melalui mass assignment
    protected $fillable = [
        'employee_id',
        'shift_id',
        'assigned_date',
    ];

    // Casting untuk atribut
    protected $casts = [
        'assigned_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke model Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Relasi ke model Shift
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
