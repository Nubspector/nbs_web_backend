<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Handle user login.
     *
     * @param string $username
     * @param string $password
     * @return array
     */
    
    public function login(string $username, string $password): array
    {
        $user = Customer::where('username', $username)->first() ?? Employee::with('role')->where('username', $username)->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'Invalid email',
                'code' => 400,
            ];
        }

        // Jika password salah
        if (!Hash::check($password, $user->password)) {
            return [
                'status' => 'error',
                'message' => 'Invalid credentials',
                'code' => 401,
            ];
        }

        // Menentukan role atau tipe user (Customer/Employee)
        $roleOrType = $user instanceof Employee ? $user->role->name : 'customer';
        
        // Jika login berhasil
        return [
            'status' => 'success',
            'message' => $user instanceof Employee ? 'Employee login successful' : 'Customer login successful',
            'role' => $roleOrType,
            'data' => $user,
            'token' => $user->createToken('authToken')->accessToken,
            'code' => 200,
        ];
    }
}
