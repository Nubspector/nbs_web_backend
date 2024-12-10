<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionService
{
    public function getAllTransactions($page = 1, $limit = 10, $search = null)
    {
        $query = Transaction::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('transaction_type', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createTransaction(array $data)
    {
        return Transaction::create($data);
    }

    public function getTransactionById(string $id)
    {
        return Transaction::findOrFail($id);
    }

    public function updateTransaction(string $id, array $data)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    public function deleteTransaction(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return $transaction;
    }
}