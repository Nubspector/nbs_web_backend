<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransactionTreatmentService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class TransactionTreatmentController extends Controller
{
    protected $transactionTreatmentService;

    public function __construct(TransactionTreatmentService $transactionTreatmentService)
    {
        $this->transactionTreatmentService = $transactionTreatmentService;
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

            $transactions = $this->transactionTreatmentService->getAllTransactions($page, $limit, $search);
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
                'treatment_id' => 'required|exists:treatment,id',
                'points_used' => 'required|numeric',
                'room_id' => 'required|numeric',
                'price' => 'required|decimal',
            ]);

            $transaction = $this->transactionTreatmentService->createTransaction($validatedData);
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
            $transaction = $this->transactionTreatmentService->getTransactionById($id);
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
                'treatment_id' => 'required|exists:treatment,id',
                'points_used' => 'required|numeric',
                'room_id' => 'required|numeric',
                'price' => 'required|decimal',
            ]);

            $transaction = $this->transactionTreatmentService->updateTransaction($id, $validatedData);
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
            $transaction = $this->transactionTreatmentService->deleteTransaction($id);
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
