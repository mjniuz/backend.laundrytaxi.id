<?php

Route::group(['prefix' => 'merchant', 'as' => 'merchant.', 'namespace' => 'Merchant'], function () {
    Route::get('/', 'MerchantController@index');
    Route::get('/detail/{id?}', 'MerchantController@detail');
    Route::get('/form/{id?}', 'MerchantController@form');
    Route::post('/save/{id?}', 'MerchantController@update');


    Route::post('/add-transaction/{id?}', 'MerchantController@addTransaction');

    Route::get('/balances', 'MerchantController@balances');
});