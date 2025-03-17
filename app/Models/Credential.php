<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    use HasFactory;

    protected $table = 'credential';

    protected $fillable = ['username', 'token', 'can_request', 'active'];

    // protected $hidden = ['token'];

    public function toArray()
    {
        $array = parent::toArray();
        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        return $array;
    }

    // Definir um valor padrão para can_request
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($credential) {
            if (is_null($credential->can_request)) {
                $credential->can_request = 0; // Padrão: pode fazer requisições
            }
        });
    }
}
