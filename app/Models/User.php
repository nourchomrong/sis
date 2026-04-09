<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class User extends Model
{
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'password',
        'role_id',
        'status',
        'owner_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
}

