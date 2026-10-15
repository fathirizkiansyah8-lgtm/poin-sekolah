<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = ['student_id', 'point_transaction_id', 'handled_by', 'follow_up_type', 'follow_up_date', 'notes'];

    public function casts(): array
    {
        return ['follow_up_date' => 'date'];
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function transaction()
    {
        return $this->belongsTo(PointTransaction::class, 'point_transaction_id');
    }
}
