<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentChange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentChangeHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_student_profile_update_is_logged_with_actor_and_changed_values(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $student = $this->createStudent();
        $newClass = $this->createSchoolClass('Kelas Baru');

        $response = $this->actingAs($admin)->put(route('students.update', $student), [
            'name' => 'Nama Baru',
            'email' => 'siswa-baru@example.test',
            'password' => 'admin-set-secret',
            'nis' => 'NIS-BARU',
            'school_class_id' => $newClass->id,
            'gender' => 'P',
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('students.index'));

        $change = StudentChange::query()->sole();

        $this->assertSame($student->id, $change->student_id);
        $this->assertSame($admin->id, $change->actor_id);
        $this->assertSame($admin->name, $change->actor_name);
        $this->assertSame('admin', $change->actor_role);
        $this->assertNotNull($change->created_at);
        $this->assertSame('Nama Siswa', $change->changes['name']['old']);
        $this->assertSame('Nama Baru', $change->changes['name']['new']);
        $this->assertSame('Kelas Lama', $change->changes['school_class']['old']);
        $this->assertSame('Kelas Baru', $change->changes['school_class']['new']);
        $this->assertSame('NIS-BARU', $change->changes['nis']['new']);
        $this->assertSame(['changed' => true], $change->changes['password']);
        $this->assertStringNotContainsString('admin-set-secret', json_encode($change->changes));
    }

    public function test_student_password_change_is_logged_without_storing_the_password(): void
    {
        $student = $this->createStudent([
            'password' => 'old-password',
        ]);
        $studentUser = $student->user;

        $response = $this->actingAs($studentUser)->put(route('student.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-private-password',
            'password_confirmation' => 'new-private-password',
        ]);

        $response->assertRedirect(route('student.password.edit'));

        $change = StudentChange::query()->sole();

        $this->assertSame($student->id, $change->student_id);
        $this->assertSame($studentUser->id, $change->actor_id);
        $this->assertSame('student', $change->actor_role);
        $this->assertSame(['password' => ['changed' => true]], $change->changes);
        $this->assertStringNotContainsString('new-private-password', json_encode($change->changes));
        $this->assertTrue(Hash::check('new-private-password', $studentUser->fresh()->password));
    }

    public function test_change_history_is_visible_to_admin_but_not_teacher(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $student = $this->createStudent();

        $student->changes()->create([
            'actor_id' => $admin->id,
            'actor_name' => $admin->name,
            'actor_role' => 'admin',
            'changes' => ['name' => ['old' => 'Nama Lama', 'new' => 'Nama Baru']],
        ]);

        $this->actingAs($admin)
            ->get(route('students.show', $student))
            ->assertOk()
            ->assertSee('Riwayat Perubahan Data')
            ->assertSee($admin->name)
            ->assertSee('Nama Lama')
            ->assertSee('Nama Baru');

        $this->post(route('logout'));

        $teacher = User::factory()->create([
            'role' => 'teacher',
            'status' => 'active',
        ]);

        $this->actingAs($teacher)
            ->get(route('students.show', $student))
            ->assertOk()
            ->assertDontSee('Riwayat Perubahan Data')
            ->assertDontSee('Nama Lama');
    }

    private function createStudent(array $attributes = []): Student
    {
        $studentUser = User::factory()->create(array_merge([
            'name' => 'Nama Siswa',
            'role' => 'student',
            'status' => 'active',
        ], $attributes));

        $schoolClass = $this->createSchoolClass('Kelas Lama');

        return Student::create([
            'user_id' => $studentUser->id,
            'school_class_id' => $schoolClass->id,
            'nis' => 'NIS-'.$studentUser->id,
            'gender' => 'L',
            'status' => 'active',
        ]);
    }

    private function createSchoolClass(string $name): SchoolClass
    {
        return SchoolClass::create([
            'name' => $name,
            'grade' => 'XI',
            'major' => 'RPL',
            'academic_year' => '2026/2027',
        ]);
    }
}
