<?php

use App\Models\Form;
use App\Models\Indicator;
use App\Models\OfferedAnswer;
use App\Models\Respondent;
use App\Models\Target;
use App\Models\UserAnswer;
use Egulias\EmailValidator\Exception\CommaInDomain;
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


Route::get('/debug', function(){
    // $target = Target::whereType('responden')->first();
    // $data = $target->form()->with(['instruments.questions' => function($q) use ($target){
    //     $q->when($target->type == 'responden' || $target->type == 'responden & petugas MONEV', function($q) use ($target){
    //         $q->with(['userAnswers' => function($q) use ($target){
    //             $q->whereRespondentId($target->respondent->id);
    //         }]);
    //     })->when($target->type == 'petugas' || $target->type == 'responden & petugas MONEV', function($q) use ($target){
    //         $q->with(['officerAnswer' => function($q) use ($target){
    //             $q->whereTargetId($target->id);
    //         }]);
    //     });
    // },'instruments.questions.offeredAnswer'])
    // ->get(); 
    // $pdf = PDF::loadView('layouts.form.respondent', compact('data','target'));
    // return $pdf->download('invoice.pdf');
    
    // return view('layouts.form.respondent',compact('data','target'));
    dd(Target::first()->scores()->get());
});

