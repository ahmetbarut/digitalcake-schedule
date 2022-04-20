<?php

use App\Http\Middleware\VerifyCsrfToken;
use Digitalcake\Scheduling\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'schedule.',
    'namespace' => '\Digitalcake\Scheduling\Controllers',
    'middleware' => 'auth.teknoza:administrator',
    'prefix' => 'administrator/schedule',
], function () {
    Route::get('/', 'ScheduleController@index')->name('index');
    Route::get('/create/email', 'ScheduleController@createEmail')->name('create.email');
    Route::post('/store/email', 'ScheduleController@storeEmail')->name('store.email');

    Route::get('/create/sms', 'ScheduleController@createSms')->name('create.sms');
    Route::post('/store/sms', 'ScheduleController@storeSms')->name('store.sms');

    Route::get('/edit/sms/{schedule}', 'ScheduleController@editSms')->name('edit.sms');
    Route::get('/edit/email/{schedule}', 'ScheduleController@editEmail')->name('edit.email');


    Route::post('/update/{id}', 'ScheduleController@update')->name('update');
    Route::get('/destroy/{schedule}', 'ScheduleController@destroy')->name('destroy');

    Route::get('/show/{schedule}', 'ScheduleController@show')->name('show');

    // Email logs
    /**
     * @deprecated
     */
    Route::get('/emails', 'TrackingController@emails')
        ->name('logs.emails');
    Route::get('/emails/show/{email}', 'TrackingController@showEmail')
        ->name('logs.email.show');

    // Route::get('/birth-day-settings', 'TrackingController@settings')
    //     ->name('logs.emails.settings');

    Route::post('/email-update', 'TrackingController@settingsUpdate')
        ->name('logs.emails.settings.update');

    Route::get('scheduling', 'TrackingController@scheduling');

    Route::get('/birth-day-settings', 'ScheduleController@birthdaySettings')
        ->name('birthday.settings');
    Route::post('/birth-day-settings', 'ScheduleController@birthdaySettingsUpdate')
        ->name('birthday.settings.update');
});

Route::post(
    config('schedule.smtp2go.webhook_url'),
    [TrackingController::class, 'index']
)->withoutMiddleware([VerifyCsrfToken::class]);
