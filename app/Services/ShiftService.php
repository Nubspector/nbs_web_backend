<?php

namespace App\Services;

use App\Models\Shift;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShiftService
{
    public function getAllShifts($page = 1, $limit = 10)
    {
        return Shift::paginate($limit, ['*'], 'page', $page);
    }

    public function createShift(array $data)
    {
        return Shift::create($data);
    }

    public function getShiftById(string $id)
    {
        return Shift::findOrFail($id);
    }

    public function updateShift(string $id, array $data)
    {
        $shift = Shift::findOrFail($id);
        $shift->update($data);
        return $shift;
    }

    public function deleteShift(string $id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return $shift;
    }
}
