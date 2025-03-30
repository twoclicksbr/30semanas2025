<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action',
        'table',
        'record_id',
        'old_data',
        'new_data',
        'id_person',
        'id_credential',
        'created_at',
    ];

    protected $hidden = [
        'id_credential'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}
