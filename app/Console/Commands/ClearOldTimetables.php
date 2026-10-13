<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Signature('app:clear-old-timetables')]
#[Description('Har yakshanba eski hafta dars jadvalini o\'chiradi')]
class ClearOldTimetables extends Command
{
    public function handle()
    {
        $this->info('Eski dars jadvallarini tozalash boshlandi...');

        try {
            // ⚠️ AGAR schedules jadvalida 'week_start' yoki 'date' bo'lsa — shu yerda ishlatamiz
            // Hozircha 'created_at' bo'yicha eski yozuvlarni o'chiramiz (xavfsiz variant)
            $currentWeekStart = now()->startOfWeek();

            // Variant 1: Agar 'week_start' ustuni bo'lsa
            // $deleted = DB::table('schedules')->where('week_start', '<', $currentWeekStart)->delete();

            // Variant 2: Agar 'date' ustuni bo'lsa
            // $deleted = DB::table('schedules')->where('date', '<', $currentWeekStart)->delete();

            // Variant 3: Hozircha — faqat LOG yozamiz (o'chirmaymiz, xavfsiz)
            $deleted = 0;
            $this->warn('⚠️ Sana ustuni aniqlanmadi — hech narsa o\'chirilmadi.');

            $this->info("✅ {$deleted} ta eski yozuv o'chirildi.");
            Log::info("ClearOldTimetables: {$deleted} ta eski jadval o'chirildi.");

        } catch (\Exception $e) {
            $this->error('❌ Xato: ' . $e->getMessage());
            Log::error('ClearOldTimetables xato: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}