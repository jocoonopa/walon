<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['user_id', 'game_id', 'character_id'];

    public $timestamps = false;

    public function game()
    {
        return $this->belongsTo('App\Model\Game');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function character()
    {
        return $this->belongsTo('App\Model\Character');
    }

    public function elections()
    {
        return $this->belongsToMany('App\Model\Election');
    }
}
