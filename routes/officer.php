<?php

use App\Http\Controllers\NotificationController;

Route::group(['prefix' => 'auth'], function(){
    Auth::routes([
        'register' => false,
        'verify' => false,
    ]);
});

Route::group(['middleware' => ['auth:officer']], function(){
    Route::group(['prefix' => 'notification', 'as'=> 'notification.'], function(){
        Route::get('/read/{notification}',[NotificationController::class, 'read'])->name('read');
        Route::get('/markallread/{guard}',[NotificationController::class, 'markAllRead'])->name('markallread');
    });

    Route::get('/','HomeController@index')->name('dashboard'); 
    Route::get('/respondentData','HomeController@respondentData')->name('dashboard.data.respondent');
    Route::get('/officerData','HomeController@officerData')->name('dashboard.data.officer');
    
    Route::group(['prefix' => 'monitoring-evaluasi','as' => 'monev.'], function(){
        Route::group(['prefix' => 'pemeriksaan','as' => 'inspection.'],function(){
            Route::get('/','InspectionController@index')->name('index');
            Route::get('/data','InspectionController@data')->name('data');
            Route::group(
                [
                    'as' => 'do.',
                    'prefix' => 'mengerjakan/{officerTarget}',
                    'middleware' => [
                        'can:manage,officerTarget' ,
                        // 'can:do,officerTarget' 
                    ]
                ], function(){
                    Route::get('/','DoController@index')->name('index');
                    Route::get('data','DoController@data')->name('data');
                    Route::post('/create','DoController@store')->middleware('can:leader,officerTarget')->name('store');
                    Route::post('/send','DoController@send')->middleware('can:leader,officerTarget')->name('send');
                    Route::get('/show','DoController@show')->name('show');

                    Route::group(['prefix' => 'pertanyaan/{instrument}','as' => 'question.'],function(){
                        Route::get('/','QuestionController@index')->name('index');
                        Route::post('/','QuestionController@store')->name('store');
                        Route::get('/show','QuestionController@show')->name('show');
                    });
            });
        });
        Route::group(['prefix' => 'riwayat-pemeriksaan','as' => 'inspection-history.'],function(){
            Route::get('/','InspectionHistoryController@index')->name('index');
            Route::get('/data','InspectionHistoryController@data')->name('data');
            Route::group(
                [
                    'as' => 'detail.',
                    'prefix' => '{officerTarget}',
                    'middleware' => ['can:manage,officerTarget','can:viewHistory,officerTarget']
                ], function(){
                Route::get('/','InspectionHistoryController@detail')->name('index');
            });
        });
    });
    Route::group(['prefix' => 'pengaturan','as' => 'setting.'], function(){
        Route::get('/', 'SettingController@index')->name('index');
        Route::put('/update', 'SettingController@update')->name('update');
    });
    
});