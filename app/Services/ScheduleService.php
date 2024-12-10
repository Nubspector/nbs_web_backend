<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ScheduleService
{
    public function getAllSchedules($page = 1, $limit = 10)
    {
        return Schedule::paginate($limit, ['*'], 'page', $page);
    }

    public function createSchedule(array $data)
    {
        return Schedule::create($data);
    }

    public function getScheduleById(string $id)
    {
        return Schedule::with(['employee', 'shift'])->findOrFail($id);
    }

    public function updateSchedule(string $id, array $data)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($data);
        return $schedule;
    }

    public function deleteSchedule(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return $schedule;
    }
}
