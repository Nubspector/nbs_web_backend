<?php

namespace App\Services;

use App\Models\Promo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PromoService
{
    public function getAllPromos($page = 1, $limit = 10, $search = null)
    {
        $query = Promo::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createPromo(array $data)
    {
        return Promo::create($data);
    }

    public function getPromoById(string $id)
    {
        return Promo::findOrFail($id);
    }

    public function updatePromo(string $id, array $data)
    {
        $promo = Promo::findOrFail($id);
        $promo->update($data);
        return $promo;
    }

    public function deletePromo(string $id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return $promo;
    }
}
