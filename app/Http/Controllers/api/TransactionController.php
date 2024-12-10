<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
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

            $transactions = $this->transactionService->getAllTransactions($page, $limit, $search);
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
                'transaction_type' => 'required|in:Top Up,Consultation',
                'status' => 'required|in:Pending,Success,Failed',
                'total_amount' => 'required|numeric',
                'payment_date' => 'required|date',
                'points_earned' => 'required|numeric',
                'customer_id' => 'required|exists:customer,id',
                'consultation_id' => 'required|exists:consultation,id',
                'promo_id' => 'required|exists:promo,id',
            ]);

            $transaction = $this->transactionService->createTransaction($validatedData);
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
            $transaction = $this->transactionService->getTransactionById($id);
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
                'transaction_type' => 'required|in:Top Up,Consultation',
                'status' => 'required|in:Pending,Success,Failed',
                'total_amount' => 'required|numeric',
                'payment_date' => 'required|date',
                'points_earned' => 'required|numeric',
                'customer_id' => 'required|exists:customer,id',
                'consultation_id' => 'required|exists:consultation,id',
                'promo_id' => 'required|exists:promo,id',
            ]);

            $transaction = $this->transactionService->updateTransaction($id, $validatedData);
            return response()->json($transaction, Response::HTTP_OK);
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
            $transaction = $this->transactionService->deleteTransaction($id);
            return response()->json($transaction, Response::HTTP_OK);
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