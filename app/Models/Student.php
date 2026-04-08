<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'student_code',
        'en_fullname',
        'kh_fullname',
        'gender',
        'dateofbirth',
        'birthplace',
        'address',
        'phone',
        'email',
        'status'
    ];

public function photo()
{
    return $this->morphOne(Photo::class, 'owner');
}
}