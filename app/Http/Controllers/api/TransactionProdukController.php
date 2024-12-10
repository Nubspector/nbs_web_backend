<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransactionProdukService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class TransactionProdukController extends Controller
{
    protected $transactionProdukService;

    public function __construct(TransactionProdukService $transactionProdukService)
    {
        $this->transactionProdukService = $transactionProdukService;
    }

    /**
     * Display a listing of the resource with pagination and optional search.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $search = $request->input('search');

            $transactions = $this->transactionProdukService->getAllTransactions($page, $limit, $search);
            return response()->json($transactions, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch transactions.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        try {
            $validatedData = $request->validate([
                'transaction_id' => 'required|exists:transaction,id',
                'product_id' => 'required|exists:product,id',
                'quantity' => 'required|numeric',
                'unit_price' => 'required|decimal',
            ]);

            $transaction = $this->transactionProdukService->createTransaction($validatedData);
            return response()->json($transaction, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create transaction.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $transaction = $this->transactionProdukService->getTransactionById($id);
            return response()->json($transaction, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transaction not found.',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch transaction.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'transaction_id' => 'required|exists:transaction,id',
                'product_id' => 'required|exists:product,id',
                'quantity' => 'required|numeric',
                'unit_price' => 'required|decimal',
            ]);

            $transaction = $this->transactionProdukService->updateTransaction($id, $validatedData);
            return response()->json($transaction, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transaction not found.',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update transaction.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->transactionProdukService->deleteTransaction($id);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transaction not found.',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete transaction.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}