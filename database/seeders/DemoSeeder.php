<?php

namespace Database\Seeders;

use App\Models\PointRule;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $class = SchoolClass::create([
            'name' => 'XI RPL 1',
            'grade' => 'XI',
            'major' => 'Rekayasa Perangkat Lunak',
            'academic_year' => '2026/2027',
        ]);

        User::create([
            'name' => 'Fathi',
            'email' => 'fathi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Octa',
            'email' => 'purmatasri@gmail.com',
            'password' => Hash::make('gurubk'),
            'role' => 'teacher',
        ]);

        $studentUser = User::create([
            'name' => 'Ahmad',
            'email' => 'amatmat@gmail.com',
            'password' => Hash::make('muridrajin'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $studentUser->id,
            'school_class_id' => $class->id,
            'nis' => '260001',
            'gender' => 'L',
            'status' => 'active',
        ]);

        $rules = [
            ['V001','violation','Disiplin','Terlambat masuk sekolah',5],
            ['V002','violation','Kerapihan','Atribut seragam tidak lengkap',10],
            ['V003','violation','Disiplin','Membolos pelajaran',20],
            ['V004','violation','Tata Tertib','Merokok di lingkungan sekolah',40],
            ['V005','violation','Tata Tertib','Berkelahi',50],
            ['R001','reward','Karakter','Aktif menjaga kebersihan kelas',5],
            ['R002','reward','Karakter','Membantu kegiatan sekolah',10],
            ['R003','reward','Akademik','Prestasi akademik tingkat sekolah',20],
            ['R004','reward','Prestasi','Juara tingkat kota/kabupaten',30],
            ['R005','reward','Prestasi','Juara tingkat provinsi',50],
            ['R006','reward','Prestasi','Juara tingkat nasional',75],
        ];

        foreach ($rules as [$code, $type, $category, $name, $points]) {
            PointRule::create(compact('code', 'type', 'category', 'name', 'points'));
        }
    }
}
