<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'administrator.schedule.',
    'namespace' => '\Digitalcake\Scheduling\Controllers',
    'middleware' => 'auth.teknoza:administrator',
    'prefix' => 'administrator/schedule',
], function () {
    Route::get('/', 'ScheduleController@index')->name('index');
    Route::get('/create/email', 'ScheduleController@createEmail')->name('create.email');
    Route::post('/store/email', 'ScheduleController@storeEmail')->name('store.email');

    Route::get('/create/sms', 'ScheduleController@createSms')->name('create.sms');
    Route::post('/store/sms', 'ScheduleController@storeSms')->name('store.sms');

    Route::get('/edit/{id}', 'ScheduleController@edit')->name('edit');
    Route::post('/update/{id}', 'ScheduleController@update')->name('update');
    Route::get('/destroy/{schedule}', 'ScheduleController@destroy')->name('destroy');

    Route::get('/show/{schedule}', 'ScheduleController@show')->name('show');
});
