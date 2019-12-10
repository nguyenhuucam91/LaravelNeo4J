<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use TSF\Neo4jClient\Facades\Neo4jClient;

Route::get('/', 'MovieController@index');

Route::resource('/movies', 'MovieController');

Route::get('/movie-actor/{id}', 'MovieActorController@index');

Route::put('/movie-actor/{id}', 'MovieActorController@update');