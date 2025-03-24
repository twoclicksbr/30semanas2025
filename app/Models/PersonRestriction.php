<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PersonRestriction extends Model
{
    protected $table = 'person_restriction';

    protected $fillable = [
        'id_credential',
        'id_person',
        'id_type_user',
        'active',
    ];

    protected $hidden = [
        'id_credential',
    ];

    public function toArray()
    {
        $array = parent::toArray();
        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        return $array;
    }
}

