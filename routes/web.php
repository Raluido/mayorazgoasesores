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
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');
    });

    /**
     * Reset password routes
     */

    Route::group(['prefix' => 'forget-password'], function () {
        Route::get('/', 'ForgotPasswordController@showForgetPasswordForm')->name('forget.password.get');
        Route::post('/', 'ForgotPasswordController@submitForgetPasswordForm')->name('forget.password.post');
        Route::get('/{token}', 'ForgotPasswordController@showResetPasswordForm')->name('reset.password.get');
        Route::post('reset-password', 'ForgotPasswordController@submitResetPasswordForm')->name('reset.password.post');
    });

    Route::group(['middleware' => ['auth', 'permission']], function () {
        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');

        Route::get('/intranet', 'IntranetController@index')->name('intranet.index');

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UsersController@index')->name('users.index');
            Route::get('/create', 'UsersController@create')->name('users.create');
            Route::post('/create', 'UsersController@store')->name('users.store');
            Route::get('/{user}/show', 'UsersController@show')->name('users.show');
            Route::get('/{user}/edit', 'UsersController@edit')->name('users.edit');
            Route::patch('/{user}/update', 'UsersController@update')->name('users.update');
            Route::delete('/{user}/delete', 'UsersController@destroy')->name('users.destroy');
            Route::get('/deleteAll', 'UsersController@deleteAll')->name('users.deleteAll');
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('/editData', 'UsersController@editData')->name('user.editData');
            Route::post('/updateData', 'UsersController@updateData')->name('user.updateData');
            Route::get('/editPassword', 'UsersController@editPassword')->name('user.editPassword');
            Route::post('/updatePassword', 'UsersController@updatePassword')->name('user.updatePassword');
        });

        Route::group(['prefix' => 'employees'], function () {
            Route::get('/', 'EmployeesController@index')->name('employees.index');
            Route::get('/create', 'EmployeesController@create')->name('employees.create');
            Route::post('/create', 'EmployeesController@store')->name('employees.store');
            Route::get('/{id}/show', 'EmployeesController@show')->name('employees.show');
            Route::get('/{id}/edit', 'EmployeesController@edit')->name('employees.edit');
            Route::patch('/{id}/update', 'EmployeesController@update')->name('employees.update');
            Route::delete('/{employee}/delete', 'EmployeesController@destroy')->name('employees.destroy');
            Route::get('/deleteAll', 'EmployeesController@deleteAll')->name('employees.deleteAll');
        });

        Route::group(['prefix' => 'payrolls'], function () {
            Route::get('/uploadForm', 'PayrollsController@uploadForm')->name('payrolls.uploadForm');
            Route::post('/upload', 'PayrollsController@uploadPayrolls')->name('payrolls.uploadPayrolls');
            Route::get('/downloadForm', 'PayrollsController@downloadForm')->name('payrolls.downloadForm');
            Route::post('/download', 'PayrollsController@getData')->name('payrolls.getData');
            Route::get('/download/{month}/{year}', 'PayrollsController@downloadPayrolls')->name('payrolls.downloadPayrolls');
            Route::get('/showForm', 'PayrollsController@showForm')->name('payrolls.showForm');
            Route::get('/show', 'PayrollsController@showPayrolls')->name('payrolls.showPayrolls');
            Route::delete('/delete/{payroll}', 'PayrollsController@deletePayrolls')->name('payrolls.deletePayrolls');
            Route::get('/deleteAll', 'PayrollsController@deleteAllPayrolls')->name('payrolls.deleteAllPayrolls');
        });

        Route::group(['prefix' => 'costsimputs'], function () {
            Route::get('/uploadForm', 'CostsImputsController@uploadForm')->name('costsimputs.uploadForm');
            Route::post('/upload', 'CostsImputsController@uploadCostsImputs')->name('costsimputs.uploadCostsImputs');
            Route::get('/downloadForm', 'CostsImputsController@downloadForm')->name('costsimputs.downloadForm');
            Route::post('/download', 'CostsImputsController@getData')->name('costsimputs.getData');
            Route::get('/download/{month}/{year}', 'CostsImputsController@downloadCostsImputs')->name('costsimputs.downloadCostsImputs');
            Route::get('/showForm', 'CostsImputsController@showForm')->name('costsimputs.showForm');
            Route::get('/show', 'CostsImputsController@showCostsImputs')->name('costsimputs.showCostsImputs');
            Route::delete('/delete/{id}/{year}/{month}', 'CostsImputsController@deleteCostsImputs')->name('costsimputs.deleteCostsImputs');
            Route::get('/deleteAll', 'CostsImputsController@deleteAllCostsImputs')->name('costsimputs.deleteAllCostsImputs');
        });

        Route::group(['prefix' => 'othersdocuments'], function () {
            Route::get('/uploadForm', 'OthersDocumentsController@uploadForm')->name('othersdocuments.uploadForm');
            Route::post('/upload', 'OthersDocumentsController@uploadOthersDocuments')->name('othersdocuments.uploadOthersDocuments');
            Route::get('/downloadForm', 'OthersDocumentsController@downloadForm')->name('othersdocuments.downloadForm');
            Route::post('/downloadList', 'OthersDocumentsController@downloadList')->name('othersdocuments.downloadList');
            Route::post('/download', 'OthersDocumentsController@downloadOthersDocuments')->name('othersdocuments.downloadOthersDocuments');
            Route::get('/showForm', 'OthersDocumentsController@showForm')->name('othersdocuments.showForm');
            Route::post('/show', 'OthersDocumentsController@showOthersDocuments')->name('othersdocuments.showOthersDocuments');
            Route::delete('/delete/{otherdocument}', 'OthersDocumentsController@deleteOthersDocuments')->name('othersdocuments.deleteOthersDocuments');
            Route::get('/deleteAll', 'OthersDocumentsController@deleteAllOtherDocuments')->name('othersdocuments.deleteAllOthersDocuments');
        });

        Route::group(['prefix' => 'posts'], function () {
            Route::get('/', 'PostsController@index')->name('posts.index');
            Route::get('/create', 'PostsController@create')->name('posts.create');
            Route::get('/showAll', 'PostsController@showAll')->name('posts.showAll');
            Route::post('/store', 'PostsController@store')->name('posts.store');
            Route::get('/{post}/edit', 'PostsController@edit')->name('posts.edit');
            Route::put('/{post}', 'PostsController@update')->name('posts.update');
            Route::delete('/{post}', 'PostsController@destroy')->name('posts.destroy');
        });

        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);

        Route::get('/getYear/{year}', 'CostsImputsController@getYear')->name('intranet.getYear');
    });

    Route::get('/posts/showAll', 'PostsController@showAll')->name('posts.showAll');
    Route::get('/posts/{post}', 'PostsController@show')->name('posts.show');
});
