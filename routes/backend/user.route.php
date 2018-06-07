<?php

Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'User'], function () {
    Route::get('/', 'UserController@index');
    Route::get('/detail/{id?}', 'UserController@detail');
});