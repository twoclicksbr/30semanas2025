<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Credential extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'credential'; // <- Aqui Laravel sabe qual tabela usar

    protected $fillable = ['username', 'token', 'active'];

    public function toArray()
    {
        $array = parent::toArray();
        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        return $array;
    }
}
