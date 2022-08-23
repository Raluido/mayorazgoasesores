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
            Route::post('/upload', 'PayrollsController@uploadPayrolls')->name('payrolls.uploadPayrolls');
            Route::get('/downloadForm', 'PayrollsController@downloadForm')->name('payrolls.downloadForm');
            Route::post('/download', 'PayrollsController@getData')->name('payrolls.getData');
            Route::get('/download/{month}/{year}', 'PayrollsController@downloadPayrolls')->name('payrolls.downloadPayrolls');
            Route::get('/showForm', 'PayrollsController@showForm')->name('payrolls.showForm');
            Route::post('/show', 'PayrollsController@showPayrolls')->name('payrolls.showPayrolls');
            Route::delete('/delete/{payroll}/{monthyear}', 'PayrollsController@deletePayrolls')->name('payrolls.deletePayrolls');
            Route::get('/deleteAll/{month}/{year}', 'PayrollsController@deleteAllPayrolls')->name('payrolls.deleteAllPayrolls');
        });

        Route::group(['prefix' => 'costsimputs'], function () {
            Route::get('/uploadForm', 'CostsImputsController@uploadForm')->name('costsimputs.uploadForm');
            Route::post('/upload', 'CostsImputsController@uploadCostsImputs')->name('costsimputs.uploadCostsImputs');
            Route::get('/downloadForm', 'CostsImputsController@downloadForm')->name('costsimputs.downloadForm');
            Route::post('/download', 'CostsImputsController@getData')->name('costsimputs.getData');
            Route::get('/download/{month}/{year}/{nif}', 'CostsImputsController@downloadCostsImputs')->name('costsimputs.downloadCostsImputs');
            Route::get('/download/{month}/{year}', 'CostsImputsController@downloadAllCostsImputs')->name('costsimputs.downloadAllCostsImputs');
            Route::get('/showForm', 'CostsImputsController@showForm')->name('costsimputs.showForm');
            Route::post('/show', 'CostsImputsController@showCostsImputs')->name('costsimputs.showCostsImputs');
            Route::delete('/delete/{costsimput}/{monthyear}', 'CostsImputsController@deleteCostsImputs')->name('costsimputs.deleteCostsImputs');
            Route::get('/deleteAll/{month}/{year}', 'CostsImputsController@deleteAllCostsImputs')->name('costsimputs.deleteAllCostsImputs');
        });

        Route::group(['prefix' => 'othersdocuments'], function () {
            Route::get('/uploadForm', 'OthersDocumentsController@uploadForm')->name('othersdocuments.uploadForm');
            Route::post('/upload', 'OthersDocumentsController@uploadOthersDocuments')->name('othersdocuments.uploadOthersDocuments');
            Route::get('/downloadForm', 'OthersDocumentsController@downloadForm')->name('othersdocuments.downloadForm');
            Route::post('/download', 'OthersDocumentsController@getData')->name('othersdocuments.getData');
            Route::get('/download/{month}/{year}', 'OthersDocumentsController@downloadOthersDocuments')->name('othersdocuments.downloadOthersDocuments');            Route::get('/showForm', 'OthersDocumentsController@showForm')->name('othersdocuments.showForm');
            Route::get('/show', 'OthersDocumentsController@showOthersDocuments')->name('othersdocuments.showOthersDocuments');
            Route::delete('/delete/{othersdocuments}/{month}/{year}', 'OthersDocumentsController@deleteOtherDocuments')->name('othersdocuments.deleteOtherDocuments');
            Route::get('/deleteAll/{month}/{year}', 'OthersDocumentsController@deleteAllOtherDocuments')->name('othersdocuments.deleteAllOtherDocuments');
        });

        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);

    });
});
