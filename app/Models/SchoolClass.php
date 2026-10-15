<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = ['name', 'grade', 'major', 'academic_year'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
