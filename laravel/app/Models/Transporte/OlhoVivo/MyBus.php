<?php

namespace App\Models\Transporte\OlhoVivo;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MyBus extends Model
{
    protected $table      = 'my_buses';
    protected $primaryKey = 'id'; //
    public    $timestamps = true; // Habilita timestamps se necessário

    protected $fillable = [
        'id',
        'cl',
        'lc',
        'lt',
        'sl',
        'tl',
        'tp',
        'ts',
        'name_bus',
        'user_id', 
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}