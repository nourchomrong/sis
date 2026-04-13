<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $primaryKey = 'term_id';
    protected $fillable = [
        'name',
        'type',
        'start_date',
        'end_date',
    ];
    
}
