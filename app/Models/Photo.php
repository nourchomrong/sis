<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $primaryKey = 'photo_id';

    protected $fillable = [
        'owner_id',
        'owner_type',
        'photo_path',
        'status'
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
