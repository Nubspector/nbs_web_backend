<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleService
{
    public function getAllRoles($page = 1, $limit = 10)
    {
        return Role::paginate($limit, ['*'], 'page', $page);
    }

    public function createRole(array $data)
    {
        return Role::create($data);
    }

    public function getRoleById(string $id)
    {
        return Role::findOrFail($id);
    }

    public function updateRole(string $id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update($data);
        return $role;
    }

    public function deleteRole(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $role;
    }
}
