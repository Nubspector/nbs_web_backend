<?php

namespace App\Services;

use App\Models\TransactionTreatment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionTreatmentService
{
    public function getAllTransactions($page = 1, $limit = 10, $search = null)
    {
        $query = TransactionTreatment::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('transaction_id', 'LIKE', "%{$search}%")
                ->orWhere('treatment_id', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createTransaction(array $data)
    {
        return TransactionTreatment::create($data);
    }

    public function getTransactionById(string $id)
    {
        return TransactionTreatment::findOrFail($id);
    }

    public function updateTransaction(string $id, array $data)
    {
        $transaction = TransactionTreatment::findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    public function deleteTransaction(string $id)
    {
        $transaction = TransactionTreatment::findOrFail($id);
        $transaction->delete();
        return $transaction;
    }
}