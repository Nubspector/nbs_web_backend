<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\TransactionProduct;
use App\Models\TransactionTreatment;
use App\Models\Consultation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LaporanService
{
    //Customer Baru Per Bulan
    public function laporanCustomerBaruPerBulan($validatedData)
    {
        $query = Customer::query();
        
        $query->whereYear('created_at', $validatedData['tahun']);
        
        $laporan = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyQuery = clone $query;
            $monthlyQuery->whereMonth('created_at', $month);
            $laporan[] = [
            'tahun' => $validatedData['tahun'],
            'bulan' => $month,
            'new_customer' => $monthlyQuery->count('id')
            ];
        }

        return $laporan;
    }

    //Pendapatan Per Bulan
    public function laporanPendapatanPerBulan($validatedData)
    {
        $query = Transaction::query();

        $query->whereYear('created_at', $validatedData['tahun']);
        

        $laporan = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyQuery = clone $query;
            $monthlyQuery->whereMonth('created_at', $month);
            $laporan[] = [
            'tahun' => $validatedData['tahun'],
            'bulan' => $month,
            'total_amount' => $monthlyQuery->sum('total_amount')
            ];
        }

        return $laporan;
    }

    //Produk terlaris pada bulan tertentu
    public function laporanProdukTerlarisPerBulan($validatedData)
    {
        $query = Transaction::query();

        $query->whereMonth('transaction.created_at', $validatedData['bulan'])
            ->whereYear('transaction.created_at', $validatedData['tahun']);
        
        $laporan = $query->join('transactionproduct', 'transaction.id', '=', 'transactionproduct.transaction_id')
            ->join('product', 'transactionproduct.product_id', '=', 'product.id')
            ->selectRaw('transactionproduct.product_id, SUM(transactionproduct.quantity) as total_quantity, product.name')
            ->groupBy('transactionproduct.product_id', 'product.name') 
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return $laporan;
    }

    //Perawatan terlaris
    public function laporanPerawatanTerlarisPerBulan($validatedData)
    {
        $query = Transaction::query();

        $query->whereMonth('transaction.created_at', $validatedData['bulan'])
            ->whereYear('transaction.created_at', $validatedData['tahun']);
        
        $laporan = $query->join('transactiontreatment', 'transaction.id', '=', 'transactiontreatment.transaction_id')
            ->join('treatment', 'transactiontreatment.treatment_id', '=', 'treatment.id')
            ->selectRaw('transactiontreatment.treatment_id, COUNT(transactiontreatment.id) as total_treatment, treatment.name')
            ->groupBy('transactiontreatment.treatment_id', 'treatment.name') 
            ->orderByDesc('total_treatment')
            ->limit(10)
            ->get();

        return $laporan;  
    }

    //Jumlah customer yang mengambil perawatan per dokter pada bulan tertentu
    public function laporanCustomerPerawatanPerDokterPerBulan($validatedData)
    {
        $query = Consultation::query();
        
        $query->whereMonth('consultation.consultation_date', $validatedData['bulan'])
            ->whereYear('consultation.consultation_date', $validatedData['tahun']);
        

        $laporan = $query->join('employee', 'consultation.doctor_id', '=', 'employee.id')
            ->selectRaw('doctor_id, COUNT(customer_id) as total_customer, employee.name')
            ->groupBy('doctor_id', 'employee.name')
            ->orderByDesc('total_customer')
            ->get();

        return $laporan; 
    }
}