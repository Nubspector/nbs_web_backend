<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Customer;

class AuthController extends Controller
{
    protected $authService;

    /**
     * Create a new AuthController instance.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle login request for customers or employees.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Panggil service login
        $result = $this->authService->login($credentials['username'], $credentials['password']);

        // Kembalikan response JSON dengan kode status
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'role' => $result['role'] ?? null,
            'data' => $result['data'],
            'token' => $result['token'] ?? null,
        ], $result['code']);
    }

    public function cekLogin()
    {

        // Cek login untuk Employee
        $user = Auth::user();
        return response()->json([
            "response" => $user
        ]);
        if(isset($user->role_id)){
            $employee = Auth::guard('employee-api')->user();
            return response([
                "success" => true,
                "type" => "employee",
                "data" => $employee
            ]);
        } else {
            $customer = Auth::guard('customer-api')->user();
            return response([
                "success" => true,
                "type" => "customer",
                "data" => $customer
            ]);
        }
    }


    /**
     * Handle logout request.
     */

    public function logout(Request $request)
    {
        // Cek apakah Customer yang login
        if ($customer = Auth::guard('customer-api')->user()) {
            // Revoke token yang aktif
            $request->user('customer-api')->token()->revoke();

            return response([
                "success" => true,
                "message" => "Customer logged out successfully",
            ]);
        }

        // Cek apakah Employee yang login
        if ($employee = Auth::guard('employee-api')->user()) {
            // Revoke token yang aktif
            $request->user('employee-api')->token()->revoke();

            return response([
                "success" => true,
                "message" => "Employee logged out successfully",
            ]);
        }

        // Jika tidak ada user yang sedang login
        return response([
            "success" => false,
            "message" => "No user is authenticated"
        ], 401);
    }
}
