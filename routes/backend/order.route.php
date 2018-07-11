<?php

Route::group(['prefix' => 'order', 'as' => 'order.', 'namespace' => 'Order'], function () {
    Route::get('/', 'OrderController@index');
    Route::get('/detail/{id?}', 'OrderController@detail');
    Route::get('/reject/{id?}', 'OrderController@rejectForm');
    Route::post('/reject-save/{id?}', 'OrderController@rejectSave');


    Route::get('/update/{id?}', 'OrderController@updateForm');
    Route::post('/update-save/{id?}', 'OrderController@updatePost');


    Route::get('/create/{id?}', 'OrderController@createForm');
    Route::post('/save/{id?}', 'OrderController@create');
    Route::get('/custom-sms/{id?}', 'OrderController@customSmsForm');
    Route::post('/custom-sms-save/{id?}', 'OrderController@customSmsSave');
});