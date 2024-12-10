<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoomService
{
    public function getAllRooms($page = 1, $limit = 10, $search = null)
    {
        $query = Room::query();

        // Menambahkan filter pencarian jika ada
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('type', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%");
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function createRoom(array $data)
    {
        return Room::create($data);
    }

    public function getRoomById(string $id)
    {
        return Room::findOrFail($id);
    }

    public function updateRoom(string $id, array $data)
    {
        $room = Room::findOrFail($id);
        $room->update($data);
        return $room;
    }

    public function deleteRoom(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return $room;
    }
}
