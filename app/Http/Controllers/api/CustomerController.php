<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
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

            $customers = $this->customerService->getAllCustomers($page, $limit, $search);
            return response()->json($customers, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch customers.',
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
                'name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:Male,Female',
                'address' => 'nullable|string',
                'phone_number' => 'required|string|max:15',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('employee', 'email'),
                    Rule::unique('customer', 'email'), // Validasi unik di tabel customer
                ],
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('employee', 'username'),
                    Rule::unique('customer', 'username'), // Validasi unik di tabel customer
                ],
                'password' => 'required|string|min:6|confirmed',
                'allergy' => 'nullable|string',
            ]);

            $customer = $this->customerService->createCustomer($validatedData);
            return response()->json($customer, Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create customer.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource by ID.
     */
    public function show(string $id)
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            return response()->json($customer, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Customer not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch customer.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:Male,Female',
                'address' => 'nullable|string',
                'phone_number' => 'required|string|max:15',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('employee', 'email'), // Ignor email employee yang sedang di-update
                    Rule::unique('customer', 'email')->ignore($id), // Tetap validasi unik di tabel customer
                ],
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('employee', 'username'), // Ignor username employee yang sedang di-update
                    Rule::unique('customer', 'username')->ignore($id), // Tetap validasi unik di tabel customer
                ],
                'password' => 'nullable|string|min:6|confirmed',
                'allergy' => 'nullable|string',
                'points' => 'nullable|integer',
                'points_used' => 'nullable|integer',
            ]);
            $customer = $this->customerService->updateCustomer($id, $validatedData);
            return response()->json($customer, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Customer not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update customer.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->customerService->deleteCustomer($id);
            return response()->json([
                'message' => 'Customer deleted successfully.'
            ], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Customer not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete customer.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
