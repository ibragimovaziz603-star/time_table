<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Period;
use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::withCount('schedules');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where('name', 'ILIKE', "%{$q}%");
        }

        $rooms = $query->orderBy('name')->paginate(40)->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.form', ['room' => new Room()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:rooms,name',
        ], [
            'name.unique' => 'Bu xona allaqachon mavjud.',
        ]);

        Room::create([
            'name' => $data['name'],
        ]);

        return redirect()->route('admin.rooms.index')
                         ->with('ok', "Xona qo'shildi.");
    }

    /**
     * Xona sahifasi — shu xonadagi haftalik jadval
     */
    public function show(Request $request, Room $room)
    {
        $schedules = Schedule::with(['subject', 'teacher', 'group', 'day', 'period'])
            ->where('room_id', $room->id)
            ->get();

        // Grid: ['Du' => [1 => lesson, ...], ...]
        $grid = [];
        foreach ($schedules as $s) {
            $grid[$s->day->short][$s->period->number] = $s;
        }

        return view('admin.rooms.show', [
            'room'    => $room,
            'grid'    => $grid,
            'days'    => Day::orderBy('number')->get(),
            'periods' => Period::orderBy('number')->get(),
        ]);
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.form', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('rooms', 'name')->ignore($room->id)],
        ], [
            'name.unique' => 'Bu xona allaqachon mavjud.',
        ]);

        $room->update([
            'name' => $data['name'],
        ]);

        return redirect()->route('admin.rooms.index')
                         ->with('ok', 'Xona yangilandi.');
    }

    public function destroy(Room $room)
    {
        $name = $room->name;

        // Xona bilan bog'liq darslar ham o'chadi (cascade)
        $room->delete();

        return back()->with('ok', "\"{$name}\" xonasi va u bilan bog'liq darslar o‘chirildi.");
    }
}