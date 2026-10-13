<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;

class TestEduPage extends Command
{
    protected $signature = 'app:test-edupage';
    protected $description = 'EduPage sahifasini yuklab, HTML ni tekshirish';

    public function handle()
    {
        $this->info('EduPage yuklanmoqda... (30-60 sekund kutish mumkin)');

        try {
            $html = Browsershot::url('https://ttpu.edupage.org/timetable/')
                ->setChromePath('C:\Program Files\Google\Chrome\Application\chrome.exe')
                ->waitUntilNetworkIdle()
                ->timeout(120)
                ->bodyHtml();

            $path = storage_path('app/edupage_test.html');
            file_put_contents($path, $html);

            $this->info('✅ HTML saqlandi: ' . $path);
            $this->info('Fayl hajmi: ' . number_format(strlen($html)) . ' bayt');

        } catch (\Exception $e) {
            $this->error('❌ Xato: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}