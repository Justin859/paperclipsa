<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/getmsg','AjaxController@index');

Route::get('/fixture', 'AjaxController@get_scores');

Route::get('/stream-streamfiles', 'AjaxController@get_streamfile_status');

Route::post('/update-squash-score', 'AjaxController@update_squash_score');
Route::get('/get-squash-score', 'AjaxController@get_squash_score'); // live

Route::post('/get-squash-score-odv', 'AjaxController@get_squash_score_odv'); // on demand video
Route::post('/get-indoor-soccer-score', 'AjaxController@get_indoor_soccer_score');
