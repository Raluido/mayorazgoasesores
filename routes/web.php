<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    /**
     * Home Routes
     */
    Route::get('/', 'HomeController@index')->name('home.index');

    Route::group(['middleware' => ['guest']], function () {
        /**
         * Register Routes
         */
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');
    });

    Route::group(['middleware' => ['auth', 'permission']], function () {
        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');

        /**
         * User Routes
         */
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UsersController@index')->name('users.index');
            Route::get('/create', 'UsersController@create')->name('users.create');
            Route::post('/create', 'UsersController@store')->name('users.store');
            Route::get('/{user}/show', 'UsersController@show')->name('users.show');
            Route::get('/{user}/edit', 'UsersController@edit')->name('users.edit');
            Route::patch('/{user}/update', 'UsersController@update')->name('users.update');
            Route::delete('/{user}/delete', 'UsersController@destroy')->name('users.destroy');
            Route::get('/createAutoForm', 'UsersController@addUsersAutoForm')->name('users.addUsersAutoForm');
            Route::post('/createAuto', 'UsersController@addUsersAuto')->name('users.addUsersAuto');
        });

        /**
         * User Routes
         */
        Route::group(['prefix' => 'payrolls'], function () {
            Route::get('/uploadForm', 'PayrollsController@uploadForm')->name('payrolls.uploadForm');
            // Route::get('/generate', 'PayrollsController@generatepayrolls')->name('payrolls.generatePayrolls');
            Route::post('/upload', 'PayrollsController@uploadPayrolls')->name('payrolls.uploadPayrolls');
            Route::get('/downloadForm', 'PayrollsController@downloadForm')->name('payrolls.downloadForm');
            Route::post('/download', 'PayrollsController@getData')->name('payrolls.getData');
            Route::get('/download/{month}/{year}', 'PayrollsController@downloadPayrolls')->name('payrolls.downloadPayrolls');
            // Route::post('/delete', 'PayrollsController@deletePayrolls')->name('payrolls.deletePayrolls');
        });

        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);

        // Route::group(['prefix' => 'pdf'], function () {
        //     Route::get('/', 'FileController@index')->name('pdf.index');
        //     Route::post('/store', 'FileController@store')->name('pdf.store');
        // });
    });
});
