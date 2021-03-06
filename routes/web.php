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

Route::group(['middleware' => 'web'], function () {

    Auth::routes();

    Route::get('/', 'HomeController@index')->name('home');

    Route::namespace('Admin')->group( function () {

        // Dashboard
        Route::get('dashboard', 'DashboardController@index');
        
        // Accounts
        Route::resource('staff', 'UserController');

        // Products
        Route::resource('customers', 'ProductController');
        Route::post('getCustomersByAjax', 'ProductController@getList')->name('getCustomerAjax');
        Route::post('customers/update_status', 'ProductController@updateStatus');
        Route::post('customers/update_csv', 'ProductController@updateByCSV');

        // Tags
        Route::resource('tags', 'TagController');

        // Setting
        Route::get('settings', 'SettingController@index');
        Route::post('settings/change_me', 'SettingController@changeMe');
        Route::post('settings/change_config', 'SettingController@changeConfig');

        // Email management
        Route::get('emails', 'EmailController@index');
        Route::post('update-status-email', 'EmailController@updateStatusEmail');
        Route::post('update-reminder-email', 'EmailController@updateReminderEmail');

    });

});