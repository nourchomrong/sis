<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $primaryKey = 'year_id';
    protected $fillable = [
        'year_name',
        'start_date',
        'end_date',
        'status',
    ];
    public function classes()
    {
        return $this->hasMany(Classes::class, 'year_id');
    }
    
}
