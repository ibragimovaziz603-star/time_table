<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============ ROLLAR ============
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin',   'label' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'teacher', 'label' => "O'qituvchi",    'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'student', 'label' => 'Talaba',         'created_at' => now(), 'updated_at' => now()],
        ]);

        // ============ ADMIN ============
        DB::table('users')->insert([
            'role_id'     => 1,
            'first_name'  => 'Super',
            'last_name'   => 'Admin',
            'middle_name' => null,
            'email'       => 'admin@ttpu.uz',
            'phone'       => null,
            'password'    => Hash::make('admin123'),
            'is_active'   => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // ============ KUNLAR ============
        DB::table('days')->insert([
            ['number' => 1, 'short' => 'Du',   'name' => 'Dushanba',   'created_at' => now(), 'updated_at' => now()],
            ['number' => 2, 'short' => 'Se',   'name' => 'Seshanba',   'created_at' => now(), 'updated_at' => now()],
            ['number' => 3, 'short' => 'Chor', 'name' => 'Chorshanba', 'created_at' => now(), 'updated_at' => now()],
            ['number' => 4, 'short' => 'Pay',  'name' => 'Payshanba',  'created_at' => now(), 'updated_at' => now()],
            ['number' => 5, 'short' => 'Ju',   'name' => 'Juma',       'created_at' => now(), 'updated_at' => now()],
            ['number' => 6, 'short' => 'Shan', 'name' => 'Shanba',     'created_at' => now(), 'updated_at' => now()],
        ]);

        // ============ DAVRLAR ============
        DB::table('periods')->insert([
            ['number' => 1, 'start_time' => '09:00', 'end_time' => '10:20', 'created_at' => now(), 'updated_at' => now()],
            ['number' => 2, 'start_time' => '10:30', 'end_time' => '11:50', 'created_at' => now(), 'updated_at' => now()],
            ['number' => 3, 'start_time' => '12:00', 'end_time' => '13:20', 'created_at' => now(), 'updated_at' => now()],
            ['number' => 4, 'start_time' => '14:20', 'end_time' => '15:40', 'created_at' => now(), 'updated_at' => now()],
            ['number' => 5, 'start_time' => '15:50', 'end_time' => '17:10', 'created_at' => now(), 'updated_at' => now()],
            ['number' => 6, 'start_time' => '17:20', 'end_time' => '18:40', 'created_at' => now(), 'updated_at' => now()],
            ['number' => 7, 'start_time' => '18:50', 'end_time' => '20:10', 'created_at' => now(), 'updated_at' => now()],
            ['number' => 8, 'start_time' => '20:20', 'end_time' => '21:40', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ============ GURUHLAR ============
        $groups = [
            "ME1-26","ME2-26","ME3-26","IT1-26","IT2-26","IT3-26","IT4-26","CIE1-26","CIE2-26",
            "ME-25","CIE-25","IT-25","ME-24","IT-24","CIE-24","ME-23","CIE-23","IT-23",
            "AE1-26","AE2-26","AE3-26","AE4-26","AE5-26","AE6-26","AE7-26","AE8-26","AE-25","AE-24",
            "VE-25","SE1-26","SE2-26","AI1-26","AI2-26","SE-25","SE-24","AKITA-24",
            "CYB1-26","CYB2-26","CYB3-26","CYB4-26","CYB5-26",
            "AD1-26","AD2-26","AD3-26","VE-26","AD-25","AD-24",
            "BM1-26","BM2-26","BM3-26","BM4-26","BM5-26","BM6-26",
            "EC1-26","EC2-26","EC3-26","BM-25","BM-24","BM-23","SE-23","AD-23",
            "SEZ-25","AKITA-25","ADZ-25","BMZ-25",
            "MSc_DS-26","MSc_TE-26","MSc_TE-25","MSc_MCRPHCM-26","MSc_MCRPHCM-25",
            "MSc_MBA-26","MSc_MBA-25",
        ];
        foreach ($groups as $g) {
            DB::table('groups')->insert([
                'name' => $g, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ============ XONALAR ============
        $rooms = [
            "101 Chem LAB","105","107","201","203","204","205","206","207","301","303","304","305","306","307",
            "303AB","302AB","402AB","403AB","404AB","305AB Phy LAB",
            "LAB 401","LAB 403","LAB 404","LAB 405","LAB 406","LAB 407",
            "Library","Library 2nd FL","Conference Hall","BLUE HALL","GREEN HALL","Grey Hall","Yellow Hall",
            "B1 Auditorium","SPECIAL LAB #1","SPECIAL LAB #2","SPECIAL LAB #3",
            "LAB C101","LAB C102","LAB C204","LAB C304","LAB C404",
            "C201","C203","C205","C206","C301","C303","C305","C306","C401","C403","C405","C406",
            "C5 Active Hall","Technopark","Sport Hall","Stadium","Online","Prerecorded","Excursion","Inno Technopark",
        ];
        foreach ($rooms as $r) {
            DB::table('rooms')->insert([
                'name' => $r, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ============ FANLAR ============
        $subjects = [
            ['code' => 'MATH1',  'name' => 'MATH 1',  'credits' => 5],
            ['code' => 'CHEM1',  'name' => 'CHEM 1',  'credits' => 4],
            ['code' => 'PHY1',   'name' => 'PHY 1',   'credits' => 4],
            ['code' => 'ENG1',   'name' => 'ENG 1',   'credits' => 3],
            ['code' => 'CMEEES', 'name' => 'CMEEES',  'credits' => 3],
            ['code' => 'DRW',    'name' => 'DRW',     'credits' => 3],
            ['code' => 'NHU',    'name' => 'NHU',     'credits' => 3],
            ['code' => 'PHS',    'name' => 'PHS',     'credits' => 2],
        ];
        foreach ($subjects as $s) {
            DB::table('subjects')->insert(array_merge($s, [
                'created_at' => now(), 'updated_at' => now(),
            ]));
        }
    }
}