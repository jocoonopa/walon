<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = 'votes';

    protected $fillable = ['election_id', 'player_id', 'is_agree'];

    protected $casts = ['is_agree' => 'boolean'];

    public $timestamps = false;

    public function election()
    {
        return $this->belongsTo('App\Model\Election');
    }
}