<?php

namespace App\Services;

use App\Models\Treatment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TreatmentService
{
    public function getAllTreatments($page = 1, $limit = 10, $search = null)
    {
        $query = Treatment::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('type', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createTreatment(array $data)
    {
        return Treatment::create($data);
    }

    public function getTreatmentById(string $id)
    {
        return Treatment::findOrFail($id);
    }

    public function updateTreatment(string $id, array $data)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->update($data);
        return $treatment;
    }

    public function deleteTreatment(string $id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();
        return $treatment;
    }
}
