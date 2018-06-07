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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'backend', 'namespace' => 'Backend', 'middleware' => ["admin.not-login"]], function() {
    Route::get('login', 'Auth\LoginController@index');
    Route::post('login/authenticate', 'Auth\LoginController@authenticate');
    Route::get('reset', 'Auth\LoginController@reset');
    Route::post('reset-password', 'Auth\LoginController@resetPassword');
    Route::get('reset-token/{token?}', 'Auth\LoginController@resetToken');
    Route::post('change-password/{token?}', 'Auth\LoginController@changePass');
});

Route::group(['prefix' => 'backend', 'namespace' => 'Backend','middleware' => ['auth.admin']], function() {
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('', 'DashboardController@index');
    Route::get('index', 'DashboardController@index');
    Route::get('dashboard', 'DashboardController@index');
    foreach (glob("../routes/backend/*.route.php") as $filename) {
        include $filename;
    }
});

Route::get('admin/login', function () { return redirect('backend/login'); });
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    // return what you want
});
