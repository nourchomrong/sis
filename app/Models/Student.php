<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_id';

    protected $fillable = [
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
public function user()
{
    return $this->morphOne(User::class, 'owner');
}
}