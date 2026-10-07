<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;

class TimetableController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'groups'   => Group::orderBy('name')->get(),
            'teachers' => User::whereHas('role', fn($q) => $q->where('name', 'teacher'))
                              ->orWhere('role_id', 1)
                              ->orderBy('last_name')->get(),
            'rooms'    => Room::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function byGroup(Group $group)
    {
        return response()->json([
            'name'     => $group->name,
            'schedule' => $this->buildGrid(['group_id' => $group->id]),
        ]);
    }

    public function byRoom(Room $room)
    {
        return response()->json([
            'name'     => $room->name,
            'schedule' => $this->buildGrid(['room_id' => $room->id]),
        ]);
    }

    public function byTeacher(User $teacher)
    {
        return response()->json([
            'name'     => $teacher->last_name . ' ' . $teacher->first_name,
            'schedule' => $this->buildGrid(['teacher_id' => $teacher->id]),
        ]);
    }

    private function buildGrid(array $where): array
    {
        $rows = Schedule::with(['subject', 'teacher', 'room', 'day', 'period'])
            ->where($where)
            ->get();

        $grid = [];
        foreach ($rows as $r) {
            $grid[$r->day->short][$r->period->number] = [
                'id'   => $r->id,
                't'    => $r->teacher->last_name,
                's'    => $r->subject->name,
                'r'    => $r->room->name,
                'type' => $r->lesson_type,
            ];
        }
        return $grid;
    }
}