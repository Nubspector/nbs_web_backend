<?php

namespace App\Services;

use App\Models\TransactionProduct;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionProdukService
{
    public function getAllTransactions($page = 1, $limit = 10, $search = null)
    {
        $query = TransactionProduct::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('transaction_id', 'LIKE', "%{$search}%")
                ->orWhere('product_id', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createTransaction(array $data)
    {
        return TransactionProduct::create($data);
    }

    public function getTransactionById(string $id)
    {
        return TransactionProduct::findOrFail($id);
    }

    public function updateTransaction(string $id, array $data)
    {
        $transaction = TransactionProduct::findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    public function deleteTransaction(string $id)
    {
        $transaction = TransactionProduct::findOrFail($id);
        $transaction->delete();
        return $transaction;
    }
}