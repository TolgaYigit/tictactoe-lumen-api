<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return view('home', ['version' => $app->version()]);
});

//$app->get('/test','GameController@index');

$app->post('/register','UserController@register');
$app->post('/login','UserController@authenticate');

//actions that require authentication
$app->group(['middleware' => 'auth'], function () use ($app) {
	//user
	$app->get('/user/{id}', ['uses' => 'UserController@getSingleUser']);
	$app->post('/user/{id}', ['uses' => 'UserController@updateUser']);
	$app->get('/users', ['uses' => 'UserController@getUserList']);
	$app->delete('/user/{user_id}', ['uses' => 'UserController@deleteUser']);

	//game
	$app->get('/games','GameController@listAvailableGames');
	$app->get('/game/{game_id}','GameController@getGameInfo');
	$app->post('/game/join','GameController@joinBattle');
	$app->post('/game/placemarker','GameController@placeMarker');
});