<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'teacher_id';
    protected $fillable = [
        'en_fullname',
        'kh_fullname',
        'gender',
        'dateofbirth',
        'birthplace',
        'address',
        'phone',
        'email',
        'status',
    ];

public function photo()
{
    return $this->morphOne(Photo::class, 'owner');
}
}
