<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Celebration extends Model
{
    use HasFactory;

    protected $table = 'celebration';

    protected $fillable = [
        'id_credential',
        'name',
        'dt_celebration',
        'link_youtube',
        'active',
    ];

    protected $hidden = ['id_credential'];

    protected $casts = [
        'dt_celebration' => 'date',
        'active' => 'integer',
    ];

    public function toArray()
    {
        $array = parent::toArray();

        // Formatando as datas corretamente
        $array['dt_celebration'] = Carbon::parse($this->dt_celebration)->format('Y-m-d');
        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

        return $array;
    }
}
