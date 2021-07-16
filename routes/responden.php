<?php

Route::group(['prefix' => 'auth'], function(){
    Auth::routes([
        'register' => false,
        'reset' => false,
        'verify' => false,
        'forgot' => false,
    ]);
    Route::group(['middleware'=> 'auth:respondent'], function(){
        Route::get('/checkpoint', 'Auth\LoginController@checkpoint')->name('checkpoint');
        Route::post('/checkpoint', 'Auth\LoginController@checkpointStore')->name('checkpoint.store');
    });
});

Route::group(['middleware' => ['auth:respondent','responden.name']], function(){
    Route::group(['middleware' => 'responden.elegible'], function(){
        Route::get('/','HomeController@index')->name('dashboard');
        Route::group(['as' => 'form.','prefix' => 'form'], function(){
            Route::get('/','FormController@index')->name('index');
            Route::get('publish','FormController@publish')->name('publish');
            Route::post('publishing','FormController@publishing')->name('publishing');
            Route::get('data','FormController@data')->name('data');
            Route::group(['as' => 'question.','prefix' => '{instrument}/question'], function(){
                Route::get('/','QuestionController@index')->name('index');
                Route::post('/','QuestionController@store')->name('store');
                Route::get('/show','QuestionController@show')->name('show');
            });
        });
    });
    Route::get('/stop', 'EndController@stop')->name('stop');
});
