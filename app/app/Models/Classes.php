<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $primaryKey = 'class_id';

    protected $fillable = [
        'class_name',
        'grade_level',
        'year_id',
        'classroom_id',
        'teacher_id',
        'status',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id', 'class_id');
    }

}
