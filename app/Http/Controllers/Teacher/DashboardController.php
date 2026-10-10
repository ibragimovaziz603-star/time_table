<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Day;
use App\Models\Grade;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** O'qituvchi dashboard — bugungi darslar */
    public function index()
    {
        $teacher = auth()->user();

        // Bugungi kun
        $dayMap = [0 => 'Yak', 1 => 'Du', 2 => 'Se', 3 => 'Chor', 4 => 'Pay', 5 => 'Ju', 6 => 'Shan'];
        $todayShort = $dayMap[now()->dayOfWeek] ?? null;
        $todayDay = $todayShort ? Day::where('short', $todayShort)->first() : null;

        // Bugungi darslar
        $todayLessons = collect();
        if ($todayDay) {
            $todayLessons = Schedule::with(['group', 'subject', 'room', 'period'])
                ->where('teacher_id', $teacher->id)
                ->where('day_id', $todayDay->id)
                ->orderBy('period_id')
                ->get();
        }

        // Statistika
        $stats = [
            'groups' => Schedule::where('teacher_id', $teacher->id)
                ->distinct('group_id')->count('group_id'),
            'subjects' => Schedule::where('teacher_id', $teacher->id)
                ->distinct('subject_id')->count('subject_id'),
            'schedules' => Schedule::where('teacher_id', $teacher->id)->count(),
        ];

        return view('teacher.dashboard', compact(
            'teacher',
            'todayDay',
            'todayLessons',
            'stats'
        ));
    }

    /** Haftalik jadval */
    public function mySchedule()
    {
        $teacher = auth()->user();

        $schedules = Schedule::with(['group', 'subject', 'room', 'day', 'period'])
            ->where('teacher_id', $teacher->id)
            ->get();

        $grid = [];
        foreach ($schedules as $s) {
            $grid[$s->day->short][$s->period->number] = $s;
        }

        return view('teacher.my-schedule', [
            'teacher' => $teacher,
            'grid' => $grid,
            'days' => Day::orderBy('number')->get(),
            'periods' => \App\Models\Period::orderBy('number')->get(),
        ]);
    }

    /** Jurnal — bugungi darslar (guruh + fan) */
    public function journal()
    {
        $teacher = auth()->user();

        // Bugungi kun
        $dayMap = [0 => 'Yak', 1 => 'Du', 2 => 'Se', 3 => 'Chor', 4 => 'Pay', 5 => 'Ju', 6 => 'Shan'];
        $todayShort = $dayMap[now()->dayOfWeek] ?? null;
        $todayDay = $todayShort ? Day::where('short', $todayShort)->first() : null;

        // Bugungi darslar (faqat shu o'qituvchi)
        $todaySchedules = collect();
        if ($todayDay) {
            $todaySchedules = Schedule::with(['group', 'subject', 'period', 'room'])
                ->where('teacher_id', $teacher->id)
                ->where('day_id', $todayDay->id)
                ->orderBy('period_id')
                ->get();
        }

        // Guruh + fan bo'yicha guruhlash (faqat bugungilar)
        $grouped = [];
        foreach ($todaySchedules as $s) {
            $key = $s->group_id . '_' . $s->subject_id;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'group' => $s->group,
                    'subject' => $s->subject,
                    'schedules' => collect(),
                ];
            }
            $grouped[$key]['schedules']->push($s);
        }

        return view('teacher.journal', [
            'teacher' => $teacher,
            'todayDay' => $todayDay,
            'grouped' => $grouped,
        ]);
    }

    /** Jurnal — aniq guruh + fan */
    public function journalEdit(Request $request, $groupId, $subjectId)
    {
        $teacher = auth()->user();
        $group = \App\Models\Group::findOrFail($groupId);
        $subject = \App\Models\Subject::findOrFail($subjectId);

        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->where('group_id', $groupId)
            ->where('subject_id', $subjectId)
            ->get();

        if ($schedules->isEmpty()) {
            abort(403, 'Siz bu guruhda bu fandan dars o‘tamaysiz.');
        }

        $students = $group->students()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Bugungi kun
        $dayMap = [0 => 'Yak', 1 => 'Du', 2 => 'Se', 3 => 'Chor', 4 => 'Pay', 5 => 'Ju', 6 => 'Shan'];
        $todayShort = $dayMap[now()->dayOfWeek] ?? null;
        $todayDay = $todayShort ? Day::where('short', $todayShort)->first() : null;

        $todaySchedules = collect();
        if ($todayDay) {
            $todaySchedules = Schedule::with(['period'])
                ->where('teacher_id', $teacher->id)
                ->where('group_id', $groupId)
                ->where('subject_id', $subjectId)
                ->where('day_id', $todayDay->id)
                ->orderBy('period_id')
                ->get();
        }

        $today = today()->toDateString();
        $scheduleIds = $todaySchedules->pluck('id');

        // ✅ Davomat — key: "student_id_schedule_id"
        $todayAttendances = [];
        if ($scheduleIds->count() > 0) {
            $attRows = Attendance::whereIn('student_id', $students->pluck('id'))
                ->whereIn('schedule_id', $scheduleIds)
                ->where('date', $today)
                ->get();

            foreach ($attRows as $a) {
                $todayAttendances[$a->student_id . '_' . $a->schedule_id] = $a->status;
            }
        }

        // ✅ Baholar — key: "student_id_schedule_id"
        $todayGrades = [];
        if ($scheduleIds->count() > 0) {
            $gradeRows = Grade::whereIn('student_id', $students->pluck('id'))
                ->where('subject_id', $subjectId)
                ->whereIn('schedule_id', $scheduleIds)
                ->where('date', $today)
                ->get();

            foreach ($gradeRows as $g) {
                $todayGrades[$g->student_id . '_' . $g->schedule_id] = $g->lesson_score;
            }
        }

        return view('teacher.journal-edit', compact(
            'teacher',
            'group',
            'subject',
            'todayDay',
            'todaySchedules',
            'students',
            'todayAttendances',
            'todayGrades'
        ));
    }
    /** Baho + davomat saqlash */
    public function journalSave(Request $request, $groupId, $subjectId)
    {
        \Log::info('=== journalSave ===');
        \Log::info('group: ' . $groupId . ' subject: ' . $subjectId);
        \Log::info('grades RAW:', $request->input('grades', []));
        \Log::info('attendance RAW:', $request->input('attendance', []));

        
        $teacher = auth()->user();

        $hasAccess = Schedule::where('teacher_id', $teacher->id)
            ->where('group_id', $groupId)
            ->where('subject_id', $subjectId)
            ->exists();

        if (!$hasAccess) {
            abort(403);
        }

        $data = $request->validate([
            'grades' => 'array',
            'grades.*' => 'array',
            'grades.*.*' => 'nullable|integer|min:0|max:10',
            'attendance' => 'array',
            'attendance.*' => 'array',
            'attendance.*.*' => 'nullable|in:b,s,nb',
        ]);

        $date = today()->toDateString();
        $savedGrades = 0;
        $savedAtt = 0;

        // Baholarni saqlash — grades[student_id][schedule_id] = score
        foreach ($data['grades'] ?? [] as $studentId => $scheduleGrades) {
            if (!is_array($scheduleGrades))
                continue;

            foreach ($scheduleGrades as $scheduleId => $score) {
                if ($score === null || $score === '') {
                    // O'chirish
                    Grade::where('student_id', $studentId)
                        ->where('subject_id', $subjectId)
                        ->where('schedule_id', $scheduleId)
                        ->where('date', $date)
                        ->delete();
                    continue;
                }

                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'schedule_id' => $scheduleId,
                        'date' => $date,
                    ],
                    ['lesson_score' => $score]
                );
                $savedGrades++;
            }
        }

        // Davomatni saqlash — attendance[student_id][schedule_id] = status
        foreach ($data['attendance'] ?? [] as $studentId => $scheduleAtts) {
            if (!is_array($scheduleAtts))
                continue;

            foreach ($scheduleAtts as $scheduleId => $status) {
                if (empty($status)) {
                    Attendance::where('student_id', $studentId)
                        ->where('schedule_id', $scheduleId)
                        ->where('date', $date)
                        ->delete();
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'schedule_id' => $scheduleId,
                        'date' => $date,
                    ],
                    ['status' => $status]
                );
                $savedAtt++;
            }
        }

        return back()->with('ok', "Saqlandi: {$savedGrades} ta baho, {$savedAtt} ta davomat.");
    }
}
