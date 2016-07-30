<?php

namespace App\Http\Controllers\Autogen;

use App\Http\Controllers\Controller;
use App\Model\Assign;
use App\Model\Game;
use App\Model\Mission;
use App\Model\Election;
use App\Model\Vote;
use App\Model\Player;
use App\Model\Character;
use App\Model\User;
use Artisan;
use DB;
use Input;

class GameController extends Controller
{
    public function index()
    {
        Artisan::call('wan:gengames', [
            'count' => Input::get('count', 100)
        ]);
    }
}
