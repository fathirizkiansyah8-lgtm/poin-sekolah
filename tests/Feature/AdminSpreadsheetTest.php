<?php

namespace Tests\Feature;

use App\Models\PointRule;
use App\Models\PointTransaction;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSpreadsheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_students_as_excel_compatible_csv(): void
    {
        $admin = $this->createUser('admin');
        $student = $this->createStudent('12345', 'Murid Export', 'murid-export@example.test');

        $response = $this->actingAs($admin)->get(route('students.export'));

        $response->assertDownload('data-siswa.csv');
        $csvLines = array_values(array_filter(explode("\n", trim($response->streamedContent()))));
        $csvRows = array_map(fn ($line) => str_getcsv($line), $csvLines);

        $this->assertSame(['nis', 'name', 'email', 'class_name', 'grade', 'gender', 'status', 'password'], array_map(
            fn ($header) => ltrim($header, "\xEF\xBB\xBF"),
            $csvRows[0]
        ));
        $this->assertSame([
            '12345', 'Murid Export', 'murid-export@example.test', 'Kelas Uji', 'XI', 'L', 'active', '',
        ], $csvRows[1]);
    }

    public function test_admin_can_download_import_template_with_required_headers(): void
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)
            ->get(route('students.index'))
            ->assertOk()
            ->assertSee('Download Template');

        $response = $this->actingAs($admin)->get(route('students.template'));

        $response->assertDownload('template-import-siswa.csv');
        $csvRows = array_map(
            fn ($line) => str_getcsv($line),
            array_values(array_filter(explode("\n", trim($response->streamedContent()))))
        );

        $this->assertCount(2, $csvRows);
        $this->assertSame([
            'nis', 'name', 'email', 'class_name', 'grade', 'gender', 'status', 'password',
        ], array_map(fn ($header) => ltrim($header, "\xEF\xBB\xBF"), $csvRows[0]));
        $this->assertSame([
            'CONTOH001',
            'Nama Siswa Contoh',
            'contoh.siswa@example.com',
            'XI RPL 1',
            'XI',
            'L',
            'active',
            'GantiPassword123',
        ], $csvRows[1]);
    }

    public function test_admin_can_import_new_student_from_csv(): void
    {
        $admin = $this->createUser('admin');
        $this->createSchoolClass('Kelas Import');
        $csv = "nis,name,email,class_name,grade,gender,status,password\n"
            .'23456,Murid Import,murid-import@example.test,Kelas Import,XI,P,active,first-password';

        $response = $this->actingAs($admin)->post(route('students.import'), [
            'file' => UploadedFile::fake()->createWithContent('students.csv', $csv),
        ]);

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success', '1 data siswa berhasil diimpor.');
        $this->assertDatabaseHas('students', [
            'nis' => '23456',
            'gender' => 'P',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'murid-import@example.test',
            'role' => 'student',
        ]);
        $this->assertTrue(Hash::check(
            'first-password',
            User::where('email', 'murid-import@example.test')->firstOrFail()->password
        ));
    }

    public function test_import_uses_grade_to_distinguish_classes_with_the_same_name(): void
    {
        $admin = $this->createUser('admin');
        $classGradeEleven = SchoolClass::create([
            'name' => 'RPL',
            'grade' => 'XI',
            'major' => 'Rekayasa Perangkat Lunak',
            'academic_year' => '2026/2027',
        ]);
        $classGradeTwelve = SchoolClass::create([
            'name' => 'RPL',
            'grade' => 'XII',
            'major' => 'Rekayasa Perangkat Lunak',
            'academic_year' => '2026/2027',
        ]);
        $csv = "nis,name,email,class_name,grade,gender,status,password\n"
            .'23459,Murid RPL XII,murid-rpl-xii@example.test,RPL,XII,L,active,first-password';

        $response = $this->actingAs($admin)->post(route('students.import'), [
            'file' => UploadedFile::fake()->createWithContent('students.csv', $csv),
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertSame(
            $classGradeTwelve->id,
            Student::where('nis', '23459')->firstOrFail()->school_class_id
        );
        $this->assertNotSame($classGradeEleven->id, $classGradeTwelve->id);
    }

    public function test_invalid_csv_does_not_import_any_student_rows(): void
    {
        $admin = $this->createUser('admin');
        $this->createSchoolClass('Kelas Import');
        $csv = "nis,name,email,class_name,grade,gender,status,password\n"
            ."23456,Murid Valid,murid-valid@example.test,Kelas Import,XI,P,active,first-password\n"
            .'23457,Murid Invalid,murid-invalid@example.test,Kelas Tidak Ada,XI,L,active,first-password';

        $response = $this->actingAs($admin)->from(route('students.index'))->post(route('students.import'), [
            'file' => UploadedFile::fake()->createWithContent('students.csv', $csv),
        ]);

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHasErrors('file');
        $this->assertDatabaseMissing('users', ['email' => 'murid-valid@example.test']);
        $this->assertDatabaseMissing('users', ['email' => 'murid-invalid@example.test']);
    }

    public function test_import_updates_existing_student_by_nis_and_preserves_password_when_blank(): void
    {
        $admin = $this->createUser('admin');
        $student = $this->createStudent('23458', 'Nama Awal', 'nama-awal@example.test');
        $student->user->update(['password' => Hash::make('keep-this-password')]);
        $this->createSchoolClass('Kelas Import');
        $csv = "nis,name,email,class_name,grade,gender,status,password\n"
            .'23458,Nama Diperbarui,nama-baru@example.test,Kelas Import,XI,P,inactive,';

        $response = $this->actingAs($admin)->post(route('students.import'), [
            'file' => UploadedFile::fake()->createWithContent('students.csv', $csv),
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertSame($student->user_id, Student::where('nis', '23458')->firstOrFail()->user_id);
        $this->assertDatabaseHas('users', [
            'id' => $student->user_id,
            'name' => 'Nama Diperbarui',
            'email' => 'nama-baru@example.test',
        ]);
        $this->assertTrue(Hash::check('keep-this-password', $student->user->fresh()->password));
        $this->assertDatabaseHas('student_changes', [
            'student_id' => $student->id,
            'actor_id' => $admin->id,
        ]);
    }

    public function test_admin_can_export_point_transactions_using_current_filters(): void
    {
        $admin = $this->createUser('admin');
        $student = $this->createStudent('34567', 'Murid Transaksi', 'murid-transaksi@example.test');
        $rule = PointRule::create([
            'code' => 'V-TEST',
            'type' => 'violation',
            'category' => 'Disiplin',
            'name' => 'Terlambat',
            'points' => 5,
            'is_active' => true,
        ]);
        PointTransaction::create([
            'student_id' => $student->id,
            'point_rule_id' => $rule->id,
            'recorded_by' => $admin->id,
            'type' => 'violation',
            'points' => 5,
            'occurred_at' => '2026-11-01 08:00:00',
            'notes' => 'Terlambat masuk',
        ]);
        PointTransaction::create([
            'student_id' => $student->id,
            'point_rule_id' => $rule->id,
            'recorded_by' => $admin->id,
            'type' => 'violation',
            'points' => 5,
            'occurred_at' => '2026-11-03 08:00:00',
            'notes' => 'Terlambat lagi',
        ]);

        $response = $this->actingAs($admin)->get(route('point-transactions.export', [
            'date_from' => '2026-11-02',
        ]));

        $response->assertDownload('transaksi-poin.csv');
        $csv = $response->streamedContent();
        $this->assertStringContainsString('2026-11-03 08:00:00', $csv);
        $this->assertStringNotContainsString('2026-11-01 08:00:00', $csv);
        $this->assertStringContainsString('Murid Transaksi', $csv);
        $this->assertStringContainsString('Terlambat lagi', $csv);
    }

    public function test_teacher_cannot_access_student_import_or_export_endpoints(): void
    {
        $teacher = $this->createUser('teacher');

        $this->actingAs($teacher)
            ->get(route('students.export'))
            ->assertForbidden();

        $this->post(route('students.import'), [
            'file' => UploadedFile::fake()->createWithContent('students.csv', 'nis,name'),
        ])->assertForbidden();
    }

    private function createUser(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
            'status' => 'active',
        ]);
    }

    private function createStudent(string $nis, string $name, string $email): Student
    {
        $user = $this->createUser('student');
        $user->update(['name' => $name, 'email' => $email]);

        return Student::create([
            'user_id' => $user->id,
            'school_class_id' => $this->createSchoolClass('Kelas Uji')->id,
            'nis' => $nis,
            'gender' => 'L',
            'status' => 'active',
        ]);
    }

    private function createSchoolClass(string $name): SchoolClass
    {
        return SchoolClass::firstOrCreate([
            'name' => $name,
        ], [
            'grade' => 'XI',
            'major' => 'RPL',
            'academic_year' => '2026/2027',
        ]);
    }
}
