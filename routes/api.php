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

Route::middleware(['auth:api', 'localization'])->group(function () {
    Route::get('/tickets', 'App\Http\Controllers\Api\TicketController@index');
    Route::patch('/tickets/progress', 'App\Http\Controllers\Api\TicketController@setProgress');
    Route::get('/tickets/in-progress', 'App\Http\Controllers\Api\TicketController@progress');
    Route::get('/tickets/in-progress-by-return', 'App\Http\Controllers\Api\TicketController@progressByReturn');
    Route::post('/tickets/done', 'App\Http\Controllers\Api\TicketController@done');
    Route::get('/tickets/done', 'App\Http\Controllers\Api\TicketController@doneList');
    Route::get('/tickets/done-by-return', 'App\Http\Controllers\Api\TicketController@doneListByReturn');
    Route::post('/tickets/done-by-return', 'App\Http\Controllers\Api\TicketController@doneByReturn');
    Route::get('/tickets/return', 'App\Http\Controllers\Api\TicketController@returnList');

    Route::get('tacks', 'App\Http\Controllers\Api\SupplierAssignmentController@pendingIndex');
    Route::patch('tacks/accept', 'App\Http\Controllers\Api\SupplierAssignmentController@approve');
    Route::get('tacks/accepted', 'App\Http\Controllers\Api\SupplierAssignmentController@approvedIndex');
    Route::patch('tacks/done', 'App\Http\Controllers\Api\SupplierAssignmentController@done');
    Route::get('tacks/done', 'App\Http\Controllers\Api\SupplierAssignmentController@doneList');

    Route::get('/profile', 'App\Http\Controllers\Api\ProfileController@index');
    Route::patch('/profile', 'App\Http\Controllers\Api\ProfileController@profileUpdate');
    Route::patch('/profile/password', 'App\Http\Controllers\Api\ProfileController@passwordUpdate');
    Route::patch('/profile/avatar', 'App\Http\Controllers\Api\ProfileController@avatarUpdate');
    Route::post('/verify-phone-number', 'App\Http\Controllers\Api\AuthController@verifyPhoneNumber');

    Route::get('/notifications', 'App\Http\Controllers\Api\NotificationController@index');
    Route::patch('/notification', 'App\Http\Controllers\Api\NotificationController@markAsRead');
});

Route::middleware(['localization'])->group(function () {
    Route::post('/register', 'App\Http\Controllers\Api\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
    Route::get('/regions', 'App\Http\Controllers\Api\RegionController@index');
});
