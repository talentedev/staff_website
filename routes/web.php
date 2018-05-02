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
        Route::resource('users', 'UserController');

        // Products
        Route::resource('products', 'ProductController');

        // Setting
        Route::get('settings', 'SettingController@index');
        Route::post('settings/change_me', 'SettingController@changeMe');

    });

});