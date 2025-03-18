<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonUser extends Model
{
    use HasFactory;

    protected $table = 'person_user';

    protected $fillable = [
        'id_credential',
        'id_person',
        'email',
        'password',
        'verification_code',
        'email_verified',
        'active',
    ];

    protected $hidden = [
        'id_credential', // Ocultar o id_credential das respostas
        'password', // Nunca exibir a senha nas respostas JSON
        'verification_code', // Código de verificação não deve ser visível
    ];

    protected $casts = [
        'email_verified' => 'boolean', // Retorna como booleano
        'active' => 'integer', // Retorna como inteiro
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'id_person');
    }

    public function credential()
    {
        return $this->belongsTo(Credential::class, 'id_credential');
    }

    public function toArray()
    {
        $array = parent::toArray();

        // Garante que created_at e updated_at sejam formatados corretamente
        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

        return $array;
    }
}
