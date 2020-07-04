<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ["auth:api"]
], function () {
    Route::get('/events/{event}', 'Api\EventController@getParticipants');
    Route::post('/events/{event}', 'Api\EventController@addParticipant');
    Route::patch('/events/{event}/participants/{participant} ', 'Api\EventController@updateParticipant');
    Route::delete('/events/{event}/participants/{participant}', 'Api\EventController@deleteParticipant');
});
