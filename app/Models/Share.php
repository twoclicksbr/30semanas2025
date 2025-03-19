<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Share extends Model
{
    use HasFactory;

    protected $table = 'share';

    protected $fillable = [
        'id_credential',
        'name',
        'id_gender',
        'id_church',
        'id_type_participation',
        'link_meet',
        'active',
    ];

    protected $hidden = ['id_credential'];

    protected $casts = [
        'active' => 'integer',
    ];

    public function toArray()
    {
        $array = parent::toArray();
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

    /**
     * Relação com type_participation
     */
    public function type_participation()
    {
        return $this->belongsTo(TypeParticipation::class, 'id_type_participation');
    }
}