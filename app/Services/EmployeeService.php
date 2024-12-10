<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeService
{
    public function getAllEmployees($page = 1, $limit = 10, $search = null)
    {
        $query = Employee::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createEmployee(array $data)
    {
        Role::findOrFail($data['role_id']);
        return Employee::create($data);
    }

    public function getEmployeeById(string $id)
    {
        return Employee::with('role')->findOrFail($id);
    }

    public function updateEmployee(string $id, array $data)
    {
      
        $employee = Employee::findOrFail($id);
        Role::findOrFail($data['role_id']);
        $employee->update($data);
        return $employee;
    }

    public function deleteEmployee(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return $employee;
    }
}
