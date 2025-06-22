<?php

namespace App\Models\Imports\OlhoVivo;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
    protected $table   = 'frequencies';
    public $timestamps = true;

    protected $fillable = [
        'trip_id', 
        'start_time', 
        'end_time', 
        'headway_secs'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
