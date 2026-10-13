<?php

namespace App\Console\Commands;

use App\Models\Day;
use App\Models\Group;
use App\Models\Period;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class FetchEduPageTimetable extends Command
{
    protected $signature = 'app:fetch-timetable {group=FYL1-26 : Guruh nomi}';
    protected $description = 'EduPage\'dan dars jadvalini yuklab, DB\'ga saqlaydi';

    private array $dayMap = [
        'Mo' => ['y' => 547.5,  'short' => 'Du'],
        'Tu' => ['y' => 802.5,  'short' => 'Se'],
        'We' => ['y' => 1057.5, 'short' => 'Chor'],
        'Th' => ['y' => 1312.5, 'short' => 'Pay'],
        'Fr' => ['y' => 1567.5, 'short' => 'Ju'],
        'Sa' => ['y' => 1822.5, 'short' => 'Shan'],
    ];

    private array $periodMap = [
        1 => 505.3125,  2 => 825.9375,  3 => 1146.5625, 4 => 1467.1875,
        5 => 1787.8125, 6 => 2108.4375, 7 => 2429.0625, 8 => 2749.6875,
    ];

    public function handle()
    {
        $groupName = $this->argument('group');

        $this->info("EduPage yuklanmoqda: {$groupName}...");

        try {
            $html = $this->fetchHtml();
            $this->info('✅ HTML yuklandi (' . number_format(strlen($html)) . ' bayt)');

            $group = Group::where('name', $groupName)->first();
            if (!$group) {
                $this->error("❌ Guruh topilmadi: {$groupName}");
                return Command::FAILURE;
            }

            $weekStart = $this->extractWeekStart($html);
            $this->info("📅 Hafta: {$weekStart}");

            $lessons = $this->parseLessons($html);
            $this->info("📚 Topilgan darslar: " . count($lessons));

            $saved = 0;
            foreach ($lessons as $lesson) {
                if ($this->saveLesson($group, $weekStart, $lesson)) {
                    $saved++;
                }
            }

            $this->info("✅ {$saved} ta dars saqlandi.");

        } catch (\Exception $e) {
            $this->error('❌ Xato: ' . $e->getMessage());
            Log::error('FetchEduPageTimetable: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function fetchHtml(): string
    {
        return Browsershot::url('https://ttpu.edupage.org/timetable/')
            ->setChromePath('C:\Program Files\Google\Chrome\Application\chrome.exe')
            ->waitUntilNetworkIdle()
            ->timeout(120)
            ->bodyHtml();
    }

    private function extractWeekStart(string $html): string
    {
        if (preg_match('/week\s+\d+\s+\((\d{2})\.(\d{2})\.(\d{4})/', $html, $m)) {
            return "{$m[3]}-{$m[2]}-{$m[1]}";
        }
        return now()->startOfWeek()->format('Y-m-d');
    }

    private function parseLessons(string $html): array
    {
        $lessons = [];

        $count = preg_match_all(
            '/<rect\s+x="([\d.]+)"\s+y="([\d.]+)"\s+width="([\d.]+)"\s+height="([\d.]+)"[^>]*><title>([^<]+)<\/title>/s',
            $html,
            $matches,
            PREG_SET_ORDER
        );

        $this->info("🔍 Regex mosliklari: " . $count);

        foreach ($matches as $m) {
            $x     = (float) $m[1];
            $y     = (float) $m[2];
            $title = trim($m[5]);

            $dayShort  = $this->findDay($y);
            $periodNum = $this->findPeriod($x);

            if (!$dayShort || !$periodNum) {
                $this->warn("⚠️ Koordinata aniqlanmadi: x={$x}, y={$y} | title=" . substr($title, 0, 40));
                continue;
            }

            $lines = array_values(array_filter(array_map('trim', explode("\n", $title))));

            $subjectName = $lines[0] ?? '';
            $subgroup    = null;
            $teacherName = null;
            $roomName    = null;

            foreach (array_slice($lines, 1) as $line) {
                if (preg_match('/^Group\s+\d+$/i', $line)) {
                    $subgroup = $line;
                } elseif (preg_match('/^[A-Z]\./i', $line)) {
                    $teacherName = $line;
                } elseif (preg_match('/^[A-Z]\d+$/i', $line)) {
                    $roomName = $line;
                }
            }

            $lessons[] = [
                'day_short'    => $dayShort,
                'period_num'   => $periodNum,
                'subject_name' => $subjectName,
                'subgroup'     => $subgroup,
                'teacher'      => $teacherName,
                'room'         => $roomName,
            ];
        }

        return $lessons;
    }

    private function findDay(float $y): ?string
    {
        // y — rect'ning tepasi. Markazni topish uchun height/2 qo'shamiz
        $centerY = $y + 127.5;

        foreach ($this->dayMap as $data) {
            if (abs($centerY - $data['y']) < 130) {
                return $data['short'];
            }
        }
        return null;
    }

    private function findPeriod(float $x): ?int
    {
        // x — rect'ning chap tomoni. Markazni topish uchun width/2 qo'shamiz
        $centerX = $x + 160;

        foreach ($this->periodMap as $num => $periodX) {
            if (abs($centerX - $periodX) < 50) {
                return $num;
            }
        }
        return null;
    }

    private function saveLesson(Group $group, string $weekStart, array $lesson): bool
    {
        $day = Day::where('short', $lesson['day_short'])->first();
        if (!$day) {
            $this->warn("❌ Kun topilmadi: {$lesson['day_short']}");
            return false;
        }

        $period = Period::where('number', $lesson['period_num'])->first();
        if (!$period) {
            $this->warn("❌ Para topilmadi: {$lesson['period_num']}");
            return false;
        }

        $subjectCode = $this->extractSubjectCode($lesson['subject_name']);
        $subject = Subject::where('code', $subjectCode)->first();

        if (!$subject) {
            $subject = Subject::create([
                'code'    => $subjectCode,
                'name'    => $lesson['subject_name'],
                'credits' => 0,
            ]);
            $this->info("➕ Yangi fan: {$subjectCode}");
        }

        $room = null;
        if ($lesson['room']) {
            $room = Room::firstOrCreate(['name' => $lesson['room']]);
        }

        Schedule::updateOrCreate(
            [
                'group_id'   => $group->id,
                'day_id'     => $day->id,
                'period_id'  => $period->id,
                'week_start' => $weekStart,
            ],
            [
                'subject_id'  => $subject->id,
                'teacher_id'  => null,
                'room_id'     => $room?->id,
                'lesson_type' => $this->extractLessonType($lesson['subject_name']),
                'semester'    => null,
            ]
        );

        return true;
    }

    private function extractSubjectCode(string $name): string
    {
        $clean = trim(preg_replace('/\s*\([^)]*\)/', '', $name));

        $map = [
            'English for Academic Purposes'      => 'EAP',
            'Developing English Language Skills' => 'DELS',
            'Mathematics for University Study'   => 'MUS',
        ];

        foreach ($map as $fullName => $code) {
            if (stripos($clean, $fullName) !== false) {
                return $code;
            }
        }

        $words = preg_split('/\s+/', $clean);
        $code = '';
        foreach ($words as $word) {
            if (!empty($word) && ctype_upper($word[0])) {
                $code .= $word[0];
            }
        }

        return $code ?: 'UNKNOWN';
    }

    private function extractLessonType(string $name): string
    {
        if (str_contains($name, '(prac)')) return 'prac';
        if (str_contains($name, '(lec)'))  return 'lec';
        return 'lec';
    }
}