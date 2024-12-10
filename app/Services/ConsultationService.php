<?php

namespace App\Services; 

use App\Models\Consultation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConsultationService 
{
    public function getAllConsultations($page = 1, $limit = 10, $search = null)
    {
        $query = Consultation::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createConsultation(array $data)
    {
        return Consultation::create($data);
    }

    public function getConsultationById(string $id)
    {
        return Consultation::findOrFail($id);
    }

    public function updateConsultation(string $id, array $data)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->update($data);
        return $consultation;
    }

    public function deleteConsultation(string $id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();
        return $consultation;
    }
}