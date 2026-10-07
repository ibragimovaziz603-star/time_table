<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AnalyticsController extends Controller
{
    /**
     * Analitika asosiy sahifasi
     */
    public function index()
    {
        $stats = [
            'students' => User::where('role_id', 3)->count(),
            'teachers' => User::where('role_id', 2)->count(),
            'visits' => \App\Models\Visit::count(),
        ];

        // Boshqa ma'lumotlar (pastdagi dropdownlar uchun)
        $groups = Group::withCount('students')
            ->orderBy('course')
            ->orderBy('name')
            ->get();

        $teachers = User::where('role_id', 2)
            ->withCount('schedules')
            ->with('subjects')
            ->orderBy('last_name')
            ->get();

        $students = User::where('role_id', 3)
            ->with('groups')
            ->orderBy('last_name')
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'groups',
            'teachers',
            'students'
        ));
    }

    /**
     * Guruh analitikasi
     */
    public function group(Request $request, Group $group)
    {
        $period = $request->get('period', 'month');

        $from = match ($period) {
            'day' => Carbon::today(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };

        // Talabalar
        $students = $group->students()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Baholar (tanlangan davrda)
        $grades = Grade::whereIn('student_id', $students->pluck('id'))
            ->where('date', '>=', $from)
            ->get();

        // Statistika
        $stats = [
            'students_count' => $students->count(),
            'avg_score' => round($grades->whereNotNull('lesson_score')->avg('lesson_score') ?? 0, 1),
            'total_lessons' => $grades->count(),
            'max_score' => $grades->max('lesson_score') ?? 0,
            'min_score' => $grades->min('lesson_score') ?? 0,
        ];

        // Har bir talaba uchun statistika
        $studentStats = [];
        foreach ($students as $s) {
            $studentGrades = $grades->where('student_id', $s->id);

            $studentStats[] = [
                'student' => $s,
                'avg_score' => round($studentGrades->whereNotNull('lesson_score')->avg('lesson_score') ?? 0, 1),
                'total_lessons' => $studentGrades->count(),
                'total_score' => $studentGrades->sum('lesson_score'),
            ];
        }

        // Guruh jadvali
        $schedules = Schedule::with(['subject', 'teacher', 'room', 'day', 'period'])
            ->where('group_id', $group->id)
            ->get();

        return view('admin.analytics.group', compact(
            'group',
            'period',
            'from',
            'students',
            'grades',
            'stats',
            'studentStats',
            'schedules'
        ));
    }

    /**
     * O'qituvchi analitikasi
     */
    public function teacher(Request $request, User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        // Guruhlari
        $groups = Group::whereIn(
            'id',
            Schedule::where('teacher_id', $teacher->id)
                ->distinct('group_id')
                ->pluck('group_id')
        )->orderBy('name')->get();

        // Fanlari
        $subjects = Subject::whereIn(
            'id',
            Schedule::where('teacher_id', $teacher->id)
                ->distinct('subject_id')
                ->pluck('subject_id')
        )->orderBy('name')->get();

        // Darslar soni
        $totalSchedules = Schedule::where('teacher_id', $teacher->id)->count();

        // Baholar
        $grades = Grade::whereIn('subject_id', $subjects->pluck('id'))->get();

        $stats = [
            'groups' => $groups->count(),
            'subjects' => $subjects->count(),
            'schedules' => $totalSchedules,
            'avg_score' => round($grades->whereNotNull('lesson_score')->avg('lesson_score') ?? 0, 1),
        ];

        return view('admin.analytics.teacher', compact(
            'teacher',
            'groups',
            'subjects',
            'stats',
            'totalSchedules'
        ));
    }
}