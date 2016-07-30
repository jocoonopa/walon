<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $table = 'elections';

    protected $fillable = ['turn', 'is_pass', 'host_id', 'mission_id'];

    protected $casts = ['is_pass' => 'boolean'];

    public $timestamps = false;

    public function host()
    {
        return $this->belongsTo('App\Model\Player');
    }

    public function votes()
    {
        return $this->hasMany('App\Model\Vote');
    }

    public function players()
    {
        return $this->belongsToMany('App\Model\Player');
    }
}
