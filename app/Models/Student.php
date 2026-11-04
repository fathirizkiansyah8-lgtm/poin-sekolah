<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = ['user_id', 'school_class_id', 'nis', 'gender', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function changes(): HasMany
    {
        return $this->hasMany(StudentChange::class);
    }
}
