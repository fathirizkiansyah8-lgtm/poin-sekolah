<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = ['student_id', 'point_rule_id', 'recorded_by', 'type', 'points', 'occurred_at', 'notes', 'evidence_path'];

    public function casts(): array
    {
        return ['occurred_at' => 'datetime']; 
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function rule()
    {
        return $this->belongsTo(PointRule::class, 'point_rule_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
}
