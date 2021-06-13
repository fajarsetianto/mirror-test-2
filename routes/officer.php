<?php

Route::group(['prefix' => 'auth'], function(){
    Auth::routes([
        'register' => false,
        'verify' => false,
    ]);
});

Route::group(['middleware' => ['auth:officer']], function(){
    Route::get('/','HomeController@index')->name('dashboard');
    Route::group(['prefix' => 'monitoring-evaluasi','as' => 'monev.'], function(){
        Route::group(['prefix' => 'pemeriksaan','as' => 'inspection.'],function(){
            Route::get('/','InspectionController@index')->name('index');
            Route::get('/data','InspectionController@data')->name('data');
        });
        Route::group(['prefix' => 'riwayat-pemeriksaan','as' => 'inspection-history.'],function(){
            Route::get('/','InspectionHistoryController@index')->name('index');
        });
    });
    
});