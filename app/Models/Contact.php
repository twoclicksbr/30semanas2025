<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contact';

    protected $fillable = [
        'id_credential',
        'id_parent',
        'route',
        'id_type_contact',
        'value',
        'active'
    ];

    /**
     * Oculta o campo id_credential nas respostas da API.
     */
    protected $hidden = [
        'id_credential',
    ];

    /**
     * Relacionamento com TypeContact (tipo de contato).
     */
    public function typeContact()
    {
        return $this->belongsTo(TypeContact::class, 'id_type_contact');
    }

    /**
     * Formata created_at e updated_at antes de exibir na API.
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        return $array;
    }
}
