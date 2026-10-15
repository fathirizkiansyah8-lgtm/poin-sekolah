<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointRule extends Model
{
    protected $fillable = ['code', 'type', 'category', 'name', 'points', 'description', 'is_active'];

    public function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }
}
