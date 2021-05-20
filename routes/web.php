<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['prefix' => 'auth'], function(){
    Auth::routes(['register' => false]);
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', function () {
        return view('pages.admin.dashboard');
    });
    Route::group(['prefix' => 'monitoring-evaluasi','as' => 'monev.'], function(){
        Route::group(['prefix' => 'form','as' => 'form.'],function(){
            Route::get('/', 'FormController@index')->name('index');
            Route::get('/data', 'FormController@data')->name('data');
            Route::get('/create', 'FormController@create')->name('create');
            Route::post('/create', 'FormController@store')->name('store');

            Route::group(['prefix' => '{form}/instruments','as' => 'instrument.'],function(){
                Route::get('/', 'InstrumentController@index')->name('index');
                Route::get('/data', 'InstrumentController@data')->name('data');
                Route::get('/create', 'InstrumentController@create')->name('create');
                Route::post('/create', 'InstrumentController@store')->name('store');
            });
        });
    });

});




Route::get('/home', 'HomeController@index')->name('home');
