<?php

use App\Models\OfferedAnswer;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\DB;
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
});

Route::get('/debug',function(){
    
    // $data = App\Models\Indicator::first()
    //             ->form->targets()->with(['respondent.answers.offeredAnswer' => function($query){
    //                 $query->select('score', DB::raw('sum("score") as scores'))
    //                          ->groupBy('question_id');
    //             }])->whereHas('respondent.answers.offeredAnswer', function($query){
    //                 $query->select('score', DB::raw('sum("score") as scores'))
    //                          ->groupBy('question_id');
    //             })->get();

    $a = 40;
    // $data = App\Models\Indicator::first()
    //             ->targets()->with(['respondent.answers' => function($q){
    //                 $q->withCount(['offeredAnswer as score' => function($q){
    //                     $q->select(DB::raw('sum(score)'));
    //                 }])
    //                 ->has('offeredAnswer')
    //                 ->having('score', '>', 20);
    //             }])
    //             ->get();

    $data = App\Models\Indicator::first()
                ->targets()->with(['respondent' => function($q){
                    // $q->withCount(['answers as answer_score' => function($q){
                    //    $q->select(DB::raw('sum(score)'));
                    // }]);
                    $q->withSum('answers','id');
                },'respondent.answers.offeredAnswer'])
                ->get();
                
    
    dd($data);
});
