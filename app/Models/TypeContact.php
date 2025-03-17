<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeContact extends Model
{
    use HasFactory;

    protected $table = 'type_contact';

    protected $fillable = [
        'id_credential',
        'name',
        'input_type',
        'mask',
        'active'
    ];

    protected $hidden = ['id_credential'];

    protected $casts = [
        'mask' => 'array', // Converte automaticamente JSON para array no Laravel
    ];

    public function toArray()
    {
        $array = parent::toArray();
        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        return $array;
    }
}
