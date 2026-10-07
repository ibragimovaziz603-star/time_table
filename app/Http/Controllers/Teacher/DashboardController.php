<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** O'qituvchi bosh sahifasi */
    public function index()
    {
        $teacher = auth()->user();

        // Bugungi darslar
        $today = Day::where('number', now()->dayOfWeek)->first();

        $todayLessons = collect();
        if ($today) {
            $todayLessons = Schedule::with(['group', 'subject', 'room', 'period'])
                ->where('teacher_id', $teacher->id)
                ->where('day_id', $today->id)
                ->orderBy('period_id')
                ->get();
        }

        // Statistika
        $totalSchedules = Schedule::where('teacher_id', $teacher->id)->count();
        $totalGroups    = Schedule::where('teacher_id', $teacher->id)
                            ->distinct('group_id')->count('group_id');
        $totalSubjects  = $teacher->subjects()->count();

        return view('teacher.dashboard', compact(
            'teacher', 'todayLessons', 'totalSchedules', 'totalGroups', 'totalSubjects'
        ));
    }

    /** Mening jadvalim — haftalik */
    public function mySchedule()
    {
        $teacher = auth()->user();

        $schedules = Schedule::with(['group', 'subject', 'room', 'day', 'period'])
            ->where('teacher_id', $teacher->id)
            ->get();

        // Grid: ['Du' => [1 => lesson, ...], ...]
        $grid = [];
        foreach ($schedules as $s) {
            $grid[$s->day->short][$s->period->number] = $s;
        }

        return view('teacher.my-schedule', [
            'teacher' => $teacher,
            'grid'    => $grid,
            'days'    => Day::orderBy('number')->get(),
            'periods' => Period::orderBy('number')->get(),
        ]);
    }

    /** Jurnal — guruhlar + fanlar ro'yxati */
    public function journal()
    {
        $teacher = auth()->user();

        // O'qituvchining guruhlari (darslardan)
        $groups = Group::whereIn('id',
            Schedule::where('teacher_id', $teacher->id)
                ->distinct('group_id')
                ->pluck('group_id')
        )->orderBy('name')->get();

        // Har bir guruh uchun fanlar
        $groupSubjects = [];
        foreach ($groups as $g) {
            $groupSubjects[$g->id] = Subject::whereIn('id',
                Schedule::where('teacher_id', $teacher->id)
                    ->where('group_id', $g->id)
                    ->distinct('subject_id')
                    ->pluck('subject_id')
            )->orderBy('name')->get();
        }

        return view('teacher.journal', compact('teacher', 'groups', 'groupSubjects'));
    }

    /** Jurnal — baho qo'yish sahifasi */
    public function journalEdit(Group $group, Subject $subject)
    {
        $teacher = auth()->user();

        // Tekshirish: bu o'qituvchi shu guruhda shu fandan dars o'tadimi?
        $hasAccess = Schedule::where('teacher_id', $teacher->id)
            ->where('group_id', $group->id)
            ->where('subject_id', $subject->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Siz bu guruhda bu fandan dars o‘tamaysiz.');
        }

        // Guruhdagi talabalar
        $students = $group->students()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Har bir talabaning baholari (bu fandan)
        $grades = Grade::where('subject_id', $subject->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('teacher.journal-edit', compact(
            'teacher', 'group', 'subject', 'students', 'grades'
        ));
    }

    /** Bahoni saqlash */
    public function journalSave(Request $request, Group $group, Subject $subject)
    {
        $teacher = auth()->user();

        $hasAccess = Schedule::where('teacher_id', $teacher->id)
            ->where('group_id', $group->id)
            ->where('subject_id', $subject->id)
            ->exists();

        if (!$hasAccess) {
            abort(403);
        }

        $data = $request->validate([
            'grades'   => 'nullable|array',
            'grades.*' => 'nullable|integer|min:0|max:10',
        ]);

        $date = now()->toDateString();

        foreach ($data['grades'] ?? [] as $studentId => $score) {
            if ($score === null || $score === '') continue;

            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $subject->id,
                    'date'       => $date,
                ],
                [
                    'lesson_score' => $score,
                ]
            );
        }

        return back()->with('ok', 'Baholar saqlandi.');
    }
}