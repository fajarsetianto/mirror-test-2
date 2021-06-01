<?php

Route::group(['prefix' => 'auth'], function(){
    Auth::routes([
        'register' => false,
        'reset' => false,
        'verify' => false,
        'forgot' => false,
    ]);

    Route::get('/checkpoint', 'Auth\LoginController@checkpoint')->name('checkpoint');
    // Route::get('/checkpoint', function(){
    //     return 'halo ha';
    // })->name('checkpoint');
    Route::post('/checkpoint', 'Auth\LoginController@checkpointStore')->name('checkpoint.store');
});
Route::group(['middleware' => ['auth:respondent','responden.name']], function(){
    Route::get('/','HomeController@index')->name('dashboard');
    Route::group(['as' => 'form.','prefix' => 'form'], function(){
        Route::get('/','FormController@index')->name('index');
        Route::get('date','FormController@data')->name('data');
    });
});
