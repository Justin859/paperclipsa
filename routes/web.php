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

use \App\Http\Middleware\CheckReferee;
use \App\Http\Middleware\CheckSuperuser;
use \App\Http\Middleware\CheckAdmin;
use \App\Http\Middleware\CheckAdminUser;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('/on-demand/purchase', 'OndemandController@vod_purchase')->name('on-demand-purchase')->middleware('auth');
Route::post('/live-now/purchase', 'LiveNowController@vod_purchase')->name('live-purchase')->middleware('auth');

Route::post('/on-demand/squash/purchase', 'OndemandController@vod_squash_purchase')->name('on-demand-squash-purchase')->middleware('auth');
Route::post('/live-now/squash/purchase', 'OndemandController@vod_squash_purchase')->name('live-squash-purchase')->middleware('auth');

Route::post('/live-event/{id}/{event_name}', 'LiveNowController@event_purchase')->name('event-purchase')->middleware('auth');
Route::post('/vod-event/{id}/{event_name}', 'OndemandController@ondemand_event_purchase')->name('ondemand-event_purchase')->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/on-demand', 'OndemandController@index')->name('on-demand');
Route::get('/on-demand/squash', 'OndemandController@index_squash')->name('on-demand-squash');
Route::get('/on-demand/{id}/{stream_filename}', 'OndemandController@vod_watch')->name('on-demand-view')->middleware('auth');
Route::get('/on-demand/squash/{id}/{streamfile_name}', 'OndemandController@vod_squash_watch')->name('on-demand-squash-view')->middleware('auth');
Route::get('/live-now', 'LiveNowController@index')->name('live-now');
Route::get('/live-now/{id}/{stream_name}', 'LiveNowController@watch')->name('live-now-watch')->middleware('auth');
Route::get('/live-event/{id}/{event_name}', 'LiveNowController@event_live_watch')->name('event-live-watch')->middleware('auth');
Route::get('/vod-event/{id}/{event_name}', 'OndemandController@ondemand_event_watch')->name('event-ondemand-watch')->middleware('auth');

Route::get('/live-now/squash/{squash_stream_id}/{stream_name}', 'LiveNowController@watch_squash')->name('live-now-squash-watch')->middleware('auth');

// Referee Dashboard

Route::post('/referee/dashboard', 'RefereeController@startStream')->name('referee-start-stream')->middleware(CheckReferee::class)->middleware('auth');
Route::post('/referee/update-scores', 'RefereeController@updateScores')->name('referee-update-scores')->middleware(CheckReferee::class)->middleware('auth');
Route::post('/referee/stop-stream', 'RefereeController@stopStream')->name('referee-stop-stream')->middleware(CheckReferee::class)->middleware('auth');

Route::get('/referee/dashboard', 'RefereeController@index')->name('referee-dashboard')->middleware(CheckReferee::class)->middleware('auth');
Route::get('/referee/dashboard/fixture/{id}/{stream_name}', 'RefereeController@viewStream')->name('referee-view-stream')->middleware(CheckReferee::class)->middleware('auth');

// End Referee Dashboard

// Squash Referee Dashboard

Route::post('/referee/squash/dashboard', 'SquashRefereeController@startStream')->name('referee-squash-start-stream')->middleware(CheckReferee::class)->middleware('auth');
Route::post('/referee/squash/update-scores', 'SquashRefereeController@updateScores')->name('referee-squash-update-scores')->middleware(CheckReferee::class)->middleware('auth');
Route::post('/referee/squash/stop-stream', 'SquashRefereeController@stopStream')->name('referee-squash-connect-streamfile')->middleware(CheckReferee::class)->middleware('auth');
Route::post('/referee/squash/start-rally', 'SquashRefereeController@startRally')->name('referee-squash-start-recording')->middleware(CheckReferee::class)->middleware('auth');
Route::post('/referee/squash/stop-rally', 'SquashRefereeController@stopRally')->name('referee-squash-stop-recording')->middleware(CheckReferee::class)->middleware('auth');
Route::post('referee/squash/start-round-recording', 'SquashRefereeController@startRoundRecording')->name('referee-squash-start-round-recording')->middleware(CheckReferee::class)->middleware('auth');
Route::post('/referee/squash/end-round-recording', 'SquashRefereeController@endRoundRecording')->name('referee-squash-end-round-recording')->middleware(CheckReferee::class)->middleware('auth');

Route::get('/referee/squash/dashboard', 'SquashRefereeController@index')->name('referee-squash-dashboard')->middleware(CheckReferee::class)->middleware('auth');
Route::get('/referee/squash/dashboard/fixture/{id}/{stream_name}', 'SquashRefereeController@viewStream')->name('referee-squash-view-stream')->middleware(CheckReferee::class)->middleware('auth');

// End Referee Squash Dashboard

// Super User Dashboard
Route::post('/superuser/dashboard/startstream', 'SuperuserController@startStream')->name('superuser-dashboard-start-stream')->middleware(CheckSuperuser::class)->middleware('auth');
Route::post('/superuser/dashboard/restart-recording', 'SuperuserController@restart_recording')->name('superuser-dashboard-restart')->middleware(CheckSuperuser::class)->middleware('auth');
Route::post('/superuser/dashboard/submit-vouchers', 'SuperuserController@submit_vouchers')->name('superuser-dashboard-submit-vouchers')->middleware(CheckSuperuser::class)->middleware('auth');

Route::get('/superuser/dashboard/', 'SuperuserController@index')->name('superuser-dashboard')->middleware(CheckSuperuser::class)->middleware('auth');
Route::get('/superuser/dashboard/event/{id}', 'SuperuserController@view_event')->name('super-dashboard-event')->middleware(CheckSuperuser::class)->middleware('auth');
Route::get('/superuser/dashboard/create-vouchers', 'SuperuserController@create_vouchers')->name('superuser-create-vouchers')->middleware(CheckSuperuser::class)->middleware('auth');
Route::get('/superuser/dashboard/download-vouchers', 'SuperuserController@download_vouchers')->name('superuser-download-vouchers')->middleware(CheckSuperuser::class)->middleware('auth');

// End Super User Dashboard

// Admin Dashboard

Route::post('/admin/dashboard', 'AdminController@create_fixture')->name('admin-create-fixture')->middleware(CheckAdmin::class)->middleware('auth');
Route::post('/admin/dashboard/delete', 'AdminController@delete_fixture')->name('admin-delete-fixture')->middleware(CheckAdmin::class)->middleware('auth');

Route::get('/admin/dashboard/', 'AdminController@index')->name('admin-dashboard')->middleware(CheckAdmin::class)->middleware('auth');

// End Admin Dashboard

// Admin and Referee pages

Route::post('/edit/fixture/{fixture_id}/save', 'AdminOrRefController@edit_fixture_save')->name('edit-fixture-save')->middleware(CheckAdminUser::class)->middleware('auth');

Route::get('/edit/fixture/{fixture_id}', 'AdminOrRefController@edit_fixture')->name('edit-fixture-view')->middleware(CheckAdminUser::class)->middleware('auth');

// End Admin and Referee pages

Route::post('/submit-promocode', 'PromocodeController@check_promocode')->name('check-promocode')->middleware('auth');
Route::post('/submit-voucher', 'VoucherController@check_voucher')->name('check-voucher')->middleware('auth');

Route::get('/submit-promocode', 'PromocodeController@enter_promocode')->name('enter-promocode')->middleware('auth');
Route::get('/submit-voucher', 'VoucherController@enter_voucher')->name('enter-voucher')->middleware('auth');

Route::get('/new/login/', function() {
    return view('auth.login');
});

Route::post('/new/register', 'Auth\RegisterController@register')->name('register-new-post');
Route::get('/new/register', 'Auth\RegisterController@showRegistrationForm')->name('register-new');

Route::get('ajax', function(){
    return view('public.ajaxtest');
});

// Download files (Super Users only at this point)

Route::get('/vouchers-csv/{venue_id}', function($venue_id){

    $venue = \App\Venue::find($venue_id);
    $table = \App\Voucher::where('venue_id', $venue_id)->where('used', false)->get();
    $filename = $venue->name."_vouchers.csv";
    $handle = fopen($filename, 'w+');
    fputcsv($handle, array('id', 'voucher key', 'venue', 'points'));

    foreach($table as $row) {
        fputcsv($handle, array($row['id'], $row['voucher_key'], $venue->name, $row['points_value']));
    }

    fclose($handle);

    $headers = array(
        'Content-Type' => 'text/csv',
    );

    return Response::download($filename, $filename, $headers);
})->middleware(CheckSuperuser::class)->middleware('auth');

// Subscription payment routes
Route::post('/subscription/checkout', 'SubscriptionController@post_subscription')->middleware('auth');
Route::get('/subscription/checkout', 'SubscriptionController@checkout_subscription')->middleware('auth');

Route::get('/subscription/success', 'SubscriptionController@success')->middleware('auth');
Route::get('/subscription/cancel', 'SubscriptionController@cancel')->middleware('auth');

Route::post('/subscription/notify', 'SubscriptionController@notify')->middleware('auth');