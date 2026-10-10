<?php

namespace App\Http\Controllers\Starosta;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Day;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function journal()
    {
        $user = auth()->user();
        $group = $user->starostaGroups()->with('students')->first();

        if (!$group) {
            abort(403, 'Siz hech qanday guruhga starosta emassiz.');
        }

        // Bugungi kun
        $dayMap = [0 => 'Yak', 1 => 'Du', 2 => 'Se', 3 => 'Chor', 4 => 'Pay', 5 => 'Ju', 6 => 'Shan'];
        $todayShort = $dayMap[now()->dayOfWeek] ?? null;
        $todayDay = $todayShort ? Day::where('short', $todayShort)->first() : null;

        // Bugungi darslar
        $todaySchedules = collect();
        if ($todayDay) {
            $todaySchedules = Schedule::with(['subject', 'teacher', 'room', 'period'])
                ->where('group_id', $group->id)
                ->where('day_id', $todayDay->id)
                ->orderBy('period_id')
                ->get();
        }

        // Talabalar
        $students = $group->students()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Bugungi davomat (grid: student_id_schedule_id => status)
        $attendances = [];
        if ($todaySchedules->count() > 0) {
            $rows = Attendance::whereIn('student_id', $students->pluck('id'))
                ->whereIn('schedule_id', $todaySchedules->pluck('id'))
                ->where('date', today()->toDateString())
                ->get();

            foreach ($rows as $r) {
                $attendances[$r->student_id . '_' . $r->schedule_id] = $r->status;
            }
        }

        return view('starosta.journal', [
            'group' => $group,
            'todaySchedules' => $todaySchedules,
            'todayName' => $todayDay?->name ?? 'Bugun',
            'students' => $students,
            'attendances' => $attendances,
        ]);
    }
    

    public function save(Request $request)
    {
        $user = auth()->user();
        $group = $user->starostaGroups()->first();

        if (!$group) {
            abort(403);
        }

        $data = $request->validate([
            'attendance' => 'array',
            'attendance.*' => 'array',
            'attendance.*.*' => 'in:b,s,nb',
        ]);

        $date = today();
        $saved = 0;

        foreach ($data['attendance'] ?? [] as $studentId => $schedules) {
            foreach ($schedules as $scheduleId => $status) {
                if (empty($status)) {
                    Attendance::where('student_id', $studentId)
                        ->where('schedule_id', $scheduleId)
                        ->where('date', $date)
                        ->delete();
                } else {
                    Attendance::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'schedule_id' => $scheduleId,
                            'date' => $date,
                        ],
                        ['status' => $status]
                    );
                    $saved++;
                }
            }
        }

        return back()->with('ok', "Davomat saqlandi ({$saved} ta yozuv).");
    }
}