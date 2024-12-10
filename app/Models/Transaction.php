<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';
    protected $primaryKey = 'id';
    public $timestamps = true; // Menggunakan created_at dan updated_at otomatis    

    protected $fillable = [
        'transaction_type',
        'status',
        'total_amount',
        'payment_date',
        'points_earned',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'consultation_id');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id');
    }

    public function transactionTreatment()
    {
        return $this->hasMany(TransactionTreatment::class, 'transaction_id');
    }

    public function transactionProduct()
    {
        return $this->hasMany(TransactionProduct::class, 'transaction_id');
    }
}
