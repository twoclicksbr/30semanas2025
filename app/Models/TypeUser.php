<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUser extends Model
{
    use HasFactory;

    protected $table = 'type_user';

    protected $fillable = ['id_credential', 'name', 'active'];

    protected $hidden = ['id_credential'];

    // Formatando timestamps
    public function toArray()
    {
        $array = parent::toArray();
        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        return $array;
    }

    // Garantir que `name` seja salvo capitalizado
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    // Garantir que `active` tenha valor padrão
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($typeUser) {
            if (is_null($typeUser->active)) {
                $typeUser->active = 1;
            }
        });
    }
}
