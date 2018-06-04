<?php

use Illuminate\Http\Request;

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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create-order', 'ApiController@createOrder');
Route::post('/order-detail/{id?}', 'ApiController@orderDetail');
Route::post('/order-list/', 'ApiController@orderList');
Route::post('/validate-phone', 'ApiController@validatePhone');
Route::post('/validate-phone-submit', 'ApiController@submitValidationCode');
Route::post('/get-packages', 'ApiController@getPackages');
Route::post('/validate-package', 'ApiController@findValidatePackage');
Route::any('/check-for-update', 'ApiController@checkForUpdate');
Route::any('/test', 'ApiController@test');