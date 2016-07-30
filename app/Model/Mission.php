<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $fillable = ['serno', 'is_success', 'game_id'];
    protected $casts = ['is_success' => 'boolean'];

    public $timestamps = false;

    public function game()
    {
        return $this->belongsTo('App\Model\Game');
    }

    public function elections()
    {
        return $this->hasMany('App\Model\Election');
    }
}
