<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Group;
use App\Models\Period;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::orderBy('name')->get();
        $selectedGroup = $request->filled('group_id')
            ? Group::find($request->group_id)
            : $groups->first();

        $schedules = collect();
        if ($selectedGroup) {
            $schedules = Schedule::with(['subject', 'teacher', 'room', 'day', 'period'])
                ->where('group_id', $selectedGroup->id)
                ->get();
        }

        $grid = [];
        foreach ($schedules as $s) {
            $grid[$s->day->short][$s->period->number] = $s;
        }

        return view('admin.schedules.index', [
            'groups'        => $groups,
            'selectedGroup' => $selectedGroup,
            'grid'          => $grid,
            'days'          => Day::orderBy('number')->get(),
            'periods'       => Period::orderBy('number')->get(),
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.schedules.form', $this->formData() + [
            'schedule'      => new Schedule(),
            'prefillDay'    => $request->day_id,
            'prefillPeriod' => $request->period_id,
            'prefillGroup'  => $request->group_id,
            'prefillRoom'   => $request->room_id,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Schedule::create($data);

        return redirect()
            ->route('admin.schedules.index', ['group_id' => $data['group_id']])
            ->with('ok', 'Dars jadvalga qo‘shildi.');
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.form', $this->formData() + [
            'schedule'      => $schedule,
            'prefillDay'    => $schedule->day_id,
            'prefillPeriod' => $schedule->period_id,
            'prefillGroup'  => $schedule->group_id,
            'prefillRoom'   => $schedule->room_id,
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $this->validated($request, $schedule->id);
        $schedule->update($data);

        return redirect()
            ->route('admin.schedules.index', ['group_id' => $data['group_id']])
            ->with('ok', 'Dars yangilandi.');
    }

    public function destroy(Schedule $schedule)
    {
        $groupId = $schedule->group_id;
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index', ['group_id' => $groupId])
            ->with('ok', 'Dars o‘chirildi.');
    }

    /**
     * Validatsiya + konflikt tekshiruvi
     */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'group_id'    => ['required', 'exists:groups,id'],
            'subject_id'  => ['required', 'exists:subjects,id'],
            'teacher_id'  => ['required', 'exists:users,id'],
            'room_id'     => ['required', 'exists:rooms,id'],
            'day_id'      => ['required', 'exists:days,id'],
            'period_id'   => ['required', 'exists:periods,id'],
            'lesson_type' => ['required', Rule::in(['lec', 'prac', 'lab'])],
            'semester'    => ['nullable', 'string', 'max:20'],
        ]);

        // ============ KONFLIKT TEKSHIRUVI ============

        // 1. Guruh bu para band?
        $groupBusy = Schedule::where('group_id', $data['group_id'])
            ->where('day_id', $data['day_id'])
            ->where('period_id', $data['period_id'])
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->with(['subject', 'teacher', 'room'])
            ->first();

        if ($groupBusy) {
            throw ValidationException::withMessages([
                'period_id' => "Bu guruhda shu para band: {$groupBusy->subject->name} ({$groupBusy->teacher->last_name}, {$groupBusy->room->name}). Avval uni o'chiring yoki boshqa para tanlang.",
            ]);
        }

        // 2. O'qituvchi bu para band?
        $teacherBusy = Schedule::where('teacher_id', $data['teacher_id'])
            ->where('day_id', $data['day_id'])
            ->where('period_id', $data['period_id'])
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->with(['group', 'subject'])
            ->first();

        if ($teacherBusy) {
            throw ValidationException::withMessages([
                'teacher_id' => "O'qituvchi shu para band: {$teacherBusy->group->name} ({$teacherBusy->subject->name}).",
            ]);
        }

        // 3. Xona bu para band?
        $roomBusy = Schedule::where('room_id', $data['room_id'])
            ->where('day_id', $data['day_id'])
            ->where('period_id', $data['period_id'])
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->with(['group', 'subject'])
            ->first();

        if ($roomBusy) {
            throw ValidationException::withMessages([
                'room_id' => "Xona shu para band: {$roomBusy->group->name} ({$roomBusy->subject->name}).",
            ]);
        }

        return $data;
    }

    private function formData(): array
    {
        return [
            'groups'   => Group::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => User::where('role_id', 2)
                              ->orderBy('last_name')
                              ->get(),
            'rooms'    => Room::orderBy('name')->get(),
            'days'     => Day::orderBy('number')->get(),
            'periods'  => Period::orderBy('number')->get(),
        ];
    }
}