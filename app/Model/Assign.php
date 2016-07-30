<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{
     protected $table = 'assigns';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'count', 'good_choices', 'bad_choices', 'places'
    ];

    protected $casts = ['good_choices' => 'array', 'bad_choices' => 'array', 'places' => 'array'];
}
