<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;

class StudentChangeLogger
{
    public function record(Student $student, User $actor, array $changes): void
    {
        if ($changes === []) {
            return;
        }

        $student->changes()->create([
            'actor_id' => $actor->id,
            'actor_name' => $actor->name,
            'actor_role' => $actor->role,
            'changes' => $changes,
        ]);
    }
}
