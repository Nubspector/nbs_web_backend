<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmployeeService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
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

            $employees = $this->employeeService->getAllEmployees($page, $limit, $search);
            return response()->json($employees, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch employees.',
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
                'role_id' => 'required|integer',
                'name' => 'required|string|max:255',
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
            ]);

            $employee = $this->employeeService->createEmployee($validatedData);
            return response()->json($employee, Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create employee.',
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
            $employee = $this->employeeService->getEmployeeById($id);
            return response()->json($employee, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employee not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch employee.',
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
                'role_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'phone_number' => 'required|string|max:15',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('employee', 'email')->ignore($id), // Ignor email employee yang sedang di-update
                    Rule::unique('customer', 'email'), // Tetap validasi unik di tabel customer
                ],
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('employee', 'username')->ignore($id), // Ignor username employee yang sedang di-update
                    Rule::unique('customer', 'username'), // Tetap validasi unik di tabel customer
                ],
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            $employee = $this->employeeService->updateEmployee($id, $validatedData);
            return response()->json($employee, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employee not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update employee.',
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
            $this->employeeService->deleteEmployee($id);
            return response()->json([
                'message' => 'Employee deleted successfully.'
            ], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employee not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete employee.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
