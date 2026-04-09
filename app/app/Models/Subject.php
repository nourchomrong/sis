<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $primaryKey = 'subject_id';

    protected $fillable = [
        'subject_code',
        'subject_name',
        'description',
        'status',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'subject_id', 'subject_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'schedule', 'subject_id', 'class_id');
    }
}
