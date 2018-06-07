<?php

Route::group(['prefix' => 'yiwugo', 'as' => 'yiwugo.', 'namespace' => 'Yiwugo'], function () {
    Route::get('/', 'YiwugoController@indexProducts');
    Route::get('/order', 'YiwugoController@indexOrders');
    Route::get('/product-detail/{id?}', 'YiwugoController@detailProduct');
    Route::post('/update-admin-fee/{id?}', 'YiwugoController@updateAdminFee');
    Route::get('/approval/{id?}', 'YiwugoController@approvalOrder');
    Route::post('/approval-save/{id?}', 'YiwugoController@approvalSave');
    Route::get('/payment/{id?}', 'YiwugoController@payment');
    Route::post('/payment-save/{id?}', 'YiwugoController@paymentSave');
    Route::get('/order-add-tracking', 'YiwugoController@orderTracking');

    Route::get('/order-detail/{id?}', 'YiwugoController@detailOrder');
    Route::get('/order-approval/{method?}/{id?}', 'YiwugoController@approvalOrder');
    Route::get('/order-add-tracking', 'YiwugoController@orderTracking');
    Route::get('/order-confirmed', 'YiwugoController@indexConfirmationPayment');
    Route::get('/discuss', 'YiwugoController@discuss');
    Route::get('/discuss/delete/{id?}', 'YiwugoController@deleteDiscuss');
});