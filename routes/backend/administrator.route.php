<?php

Route::get('/profile', 'AdminUsers\AdminUsersController@myProfile');
Route::post('/profile-update', 'AdminUsers\AdminUsersController@profileUpdate');

Route::group(['prefix' => 'administrator', 'as' => 'administrator.', 'namespace' => 'AdminUsers'], function () {
    Route::get('/', 'AdminUsersController@index');
    Route::get('/create', 'AdminUsersController@form');
    Route::post('/save', 'AdminUsersController@save');
    Route::get('/detail/{id?}', 'AdminUsersController@detail');
    Route::get('/edit/{id?}', 'AdminUsersController@form');
    Route::post('/update/{id?}', 'AdminUsersController@save');
    Route::get('/delete/{id?}', 'AdminUsersController@delete');


    Route::group(['prefix' => 'role', 'as' => 'role.'], function(){
        Route::get('/', 'AdminUsersController@roleIndex');
        Route::get('/user/{id?}', 'AdminUsersController@roleUser');
        Route::post('/user-update/{id?}', 'AdminUsersController@roleUserUpdate');
        Route::get('/create', 'AdminUsersController@roleForm');
        Route::get('/edit/{id?}', 'AdminUsersController@roleForm');
        Route::get('/permission/{id?}', 'AdminUsersController@rolePermission');
        Route::post('/permission-update/{id?}', 'AdminUsersController@rolePermissionSave');
        Route::post('/update/{id?}', 'AdminUsersController@roleSave');
        Route::post('/save', 'AdminUsersController@roleSave');
    });

    Route::group(['prefix' => 'permission', 'as' => 'permission.'], function(){
        Route::get('/', 'AdminUsersController@permissionIndex');
        Route::get('/create', 'AdminUsersController@permissionForm');
        Route::get('/edit/{id?}', 'AdminUsersController@permissionForm');
        Route::get('/delete/{id?}', 'AdminUsersController@permissionDelete');
        Route::post('/save', 'AdminUsersController@permissionSave');
        Route::post('/update/{id?}', 'AdminUsersController@permissionSave');
    });

    Route::group(['prefix' => 'group', 'as' => 'group.'], function(){
        Route::get('/', 'AdminUsersController@groupIndex');
        Route::get('/create', 'AdminUsersController@groupForm');
        Route::get('/edit/{id?}', 'AdminUsersController@groupForm');

        Route::post('/save', 'AdminUsersController@groupSave');
        Route::post('/update/{id?}', 'AdminUsersController@groupSave');

        Route::get('/delete/{id?}', 'AdminUsersController@groupDelete');
    });
});