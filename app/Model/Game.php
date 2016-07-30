<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['location', 'assign_id'];

    protected $casts = ['is_own_by_jus' => 'boolean'];

    public $timestamps = true;

    /**
     * A game can have many players
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        return $this->hasMany('App\Model\Player');
    }

    public function missions()
    {
        return $this->hasMany('App\Model\Mission');
    }

    public function assign()
    {
        return $this->belongsTo('App\Model\Assign');
    }
}
