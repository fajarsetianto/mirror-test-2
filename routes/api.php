<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'officer','namespace' => 'Officer',], function(){
    Route::group(['prefix' => 'auth'], function(){
        Route::post('login','AuthController@login');
    });
    Route::group(['prefix' => 'me','middleware' => ['auth:sanctum','type.officer']],function(){
        Route::get('/', 'HomeController@index');
        Route::group(['prefix' => 'review'], function($q){
            Route::get('/', 'ReviewController@index');
        });
        Route::group(['prefix' => 'fillable'], function($q){
            Route::get('/', 'FillableController@index');
        });
    });
});

Route::group(['prefix' => 'respondent','namespace' => 'Respondent',], function(){
    Route::group(['prefix' => 'auth'], function(){
        Route::post('login','AuthController@login');
    });
    Route::middleware(['auth:sanctum', 'type.respondent'])->group(function () {
        // Route::get('/customers/orders', 'API\Customers\OrderController@index');
    });
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
