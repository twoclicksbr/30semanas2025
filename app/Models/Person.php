<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Person extends Model
{
    use HasFactory;

    protected $table = 'person'; // Nome correto da tabela

    protected $fillable = [
        'id_credential',
        'name',
        'cpf',
        'id_church',
        'birthdate',
        'id_gender',
        'eklesia',
        'active'
    ];

    protected $hidden = [
        'id_credential' // Oculta esse campo nas respostas da API
    ];

    protected $casts = [
        'active' => 'integer',
        'birthdate' => 'date'
    ];

    /**
     * Formata os dados ao transformar em array
     */
    public function toArray()
    {
        $array = parent::toArray();

        // Garante que o CPF seja retornado apenas com números
        $array['cpf'] = preg_replace('/\D/', '', $this->cpf);

        // Formatar datas
        if ($this->birthdate) {
            $array['birthdate'] = Carbon::parse($this->birthdate)->format('Y-m-d');
        }

        $array['created_at'] = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        $array['updated_at'] = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

        return $array;
    }
    
    /**
     * Relação com Credential
     */
    public function credential()
    {
        return $this->belongsTo(Credential::class, 'id_credential');
    }

    /**
     * Relação com Church
     */
    public function church()
    {
        return $this->belongsTo(Church::class, 'id_church');
    }

    /**
     * Relação com Gender
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'id_gender');
    }
}
