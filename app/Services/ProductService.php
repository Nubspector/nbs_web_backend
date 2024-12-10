<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService
{
    public function getAllProducts($page = 1, $limit = 10, $search = null)
    {
        $query = Product::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('category', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function getProductById(string $id)
    {
        return Product::findOrFail($id);
    }

    public function updateProduct(string $id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function deleteProduct(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return $product;
    }
}
