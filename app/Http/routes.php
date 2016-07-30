<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::auth();
Route::get('/', function () {
    return redirect('home');
});
Route::get('/home', 'HomeController@index');

Route::resource('game', 'GameController');

Route::resource('games.players', 'PlayerController', [
    'parameters' => 'singular'
]);

Route::resource('games.missions', 'MissionController', [
    'parameters' => 'singular'
]);

Route::resource('missions.elections', 'ElectionController', [
    'parameters' => 'singular'
]);

Route::resource('elections.players.votes', 'VoteController', [
    'parameters' => 'singular'
]);

Route::group(['namespace' => 'Autogen', 'prefix' => 'autogen'], function() {
    Route::get('/game', ['uses' => 'GameController@index']);
});

