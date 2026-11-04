<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_their_password_update_form(): void
    {
        $student = $this->createStudent();

        $this->actingAs($student)
            ->get(route('student.password.edit'))
            ->assertOk()
            ->assertSee('Ubah Password')
            ->assertSee(route('student.password.update'));
    }

    public function test_guest_is_redirected_from_student_password_form(): void
    {
        $this->get(route('student.password.edit'))
            ->assertRedirect(route('login'));
    }

    public function test_student_can_update_their_password(): void
    {
        $student = $this->createStudent([
            'password' => 'old-password',
        ]);

        $response = $this->actingAs($student)->put(route('student.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect(route('student.password.edit'));
        $this->assertTrue(Hash::check('new-password-123', $student->fresh()->password));
    }

    public function test_student_password_update_requires_the_current_password(): void
    {
        $student = $this->createStudent([
            'password' => 'old-password',
        ]);

        $response = $this->actingAs($student)->from(route('student.password.edit'))
            ->put(route('student.password.update'), [
                'current_password' => 'incorrect-password',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ]);

        $response->assertRedirect(route('student.password.edit'));
        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('old-password', $student->fresh()->password));
    }

    public function test_non_student_cannot_access_student_password_page(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'status' => 'active',
        ]);

        $this->actingAs($teacher)
            ->get(route('student.password.edit'))
            ->assertForbidden();
    }

    public function test_student_password_must_be_confirmed_and_at_least_eight_characters(): void
    {
        $student = $this->createStudent([
            'password' => 'old-password',
        ]);

        $response = $this->actingAs($student)->from(route('student.password.edit'))
            ->put(route('student.password.update'), [
                'current_password' => 'old-password',
                'password' => 'short',
                'password_confirmation' => 'different',
            ]);

        $response->assertRedirect(route('student.password.edit'));
        $response->assertSessionHasErrors(['password']);
        $this->assertTrue(Hash::check('old-password', $student->fresh()->password));
    }

    private function createStudent(array $attributes = []): User
    {
        $studentUser = User::factory()->create(array_merge([
            'role' => 'student',
            'status' => 'active',
        ], $attributes));

        $schoolClass = SchoolClass::create([
            'name' => 'Kelas '.$studentUser->id,
            'grade' => 'XI',
            'major' => 'RPL',
            'academic_year' => '2026/2027',
        ]);

        Student::create([
            'user_id' => $studentUser->id,
            'school_class_id' => $schoolClass->id,
            'nis' => 'NIS-'.$studentUser->id,
            'gender' => 'L',
            'status' => 'active',
        ]);

        return $studentUser;
    }
}
