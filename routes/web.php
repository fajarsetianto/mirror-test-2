<?php

use App\Models\EducationalInstitution;
use App\Models\Form;
use App\Models\Indicator;
use App\Models\NonEducationalInstitution;
use App\Models\OfferedAnswer;
use App\Models\Officer;
use App\Models\OfficerAnswer;
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
    Route::get('/', function (){
        $officerCount = Officer::whereCreatedBy(auth()->user()->id)->count();
        $formCount = Form::whereCreatedBy(auth()->user()->id)->count();
        $educationalCount = EducationalInstitution::count();
        $nonEducationalCount = NonEducationalInstitution::whereCreatedBy(auth()->user()->id)->count();
        return view('pages.admin.dashboard',compact('officerCount','formCount','educationalCount','nonEducationalCount'));
    });
});


Route::get('/debug', function(){
    // $target = Target::whereType('responden')->first();
    // $data = $target->form()->with(['instruments.questions' => function($q) use ($target){
    //     $q->when($target->type == 'responden' || $target->type == 'responden & petugas MONEV', function($q) use ($target){
    //         $q->load(['userAnswers' => function($q) use ($target){
    //             $q->whereRespondentId($target->respondent->id);
    //         }]);
    //     })->when($target->type == 'petugas' || $target->type == 'responden & petugas MONEV', function($q) use ($target){
    //         $q->load(['officerAnswer' => function($q) use ($target){
    //             $q->whereTargetId($target->id);
    //         }]);
    //     });
    // },'instruments.questions.offeredAnswer'])
    // ->get(); 
    // $pdf = PDF::loadView('layouts.form.respondent', compact('data','target'));
    // return $pdf->download('invoice.pdf');
    
    // // return view('layouts.form.respondent',compact('data','target'));
    // dd(Target::first()->respondentScore());

    $form = Form::first();
    DB::enableQueryLog();
    // $data = $form->indicators()->with(['targets' => function($q){
    //     $q->whereHas('officerLeader.answers.offeredAnswer', function($q){
    //         $q->select(DB::raw("SUM(score) as scores"))
    //             ->having('scores','>=','indicators.min')
    //             ->having('scores','<=','indicators.max');
    //     })->orWhereHas('respondent.answers.offeredAnswer',function($q){
    //         $q->select(DB::raw("SUM(score) as scores"))
    //             ->havingRaw('scores <= ?',[1]);
    //         });
    // }])->get();
    // $data = $form->indicators()->with(['targets' => function($q){
    //     $q->whereHas('officerLeader.answers', function($q){
    //         $q->join('offered_answers','officer_answers.offered_answer_id','offered_answers.id')
    //             ->select(DB::raw("SUM(offered_answers.score) as scores"))
    //             ->havingRaw('scores >= indicators.min')
    //             ->havingRaw('scores <= indicators.max');
    //         })
    //     ->orWhereHas('respondent.answers', function($q){
    //         $q->join('offered_answers','user_answers.offered_answer_id','offered_answers.id')
    //             ->select(DB::raw("SUM(offered_answers.score) as scores"))
    //             ->havingRaw('scores >= indicators.min')
    //             ->havingRaw('scores <= indicators.max');
    //         });
    // }])->get();
    // $query_dump = DB::getQueryLog();
    // dd($query_dump);

    // $data = $form->indicators()->with(['targets' => function($q){
    //     $q->whereHas('scoreAnswers', function($q){
    //         $q->when($q->getModel() instanceof OfficerAnswer, function($q){
    //             $q->whereNotNull('officer_targets.submited_at')
    //                 ->join('offered_answers','officer_answers.offered_answer_id','=','offered_answers.id');
    //         }, function($q){
    //             $q->join('offered_answers','offered_answers.id','=','user_answers.offered_answer_id');
    //         });
    //         $q->select(DB::raw("SUM(offered_answers.score) as scores"))
    //             ->havingRaw('scores >= ?',[0])
    //             ->havingRaw('scores <= ?',[100]);
    //     });
    // }])->get();
    $data = $form->indicators()
        ->get()[2]
        ->targetsQualified;
        // ->targetsIn;
        // ->newTarget;

    // $data = Target::get()[0]->scoreAnswers();
// $query_dump = DB::getQueryLog()
    // dd($query_dump); 

    // $data = Target::query()
    //             ->addSelect([
    //                 'respondent_score' => OfferedAnswer::selectRaw('sum(offered_answers.score)')
    //                     ->join('user_answers','offered_answers.id','=','user_answers.offered_answer_id')
    //                     ->join('respondents','respondents.id','=','user_answers.respondent_id')
    //                     ->whereColumn('respondents.target_id','=', 'targets.id')
    //                     ->where('respondents.submited_at','<>',null)
    //                     ->where('targets.type','=','responden'),
    //                 'officers_score' => OfferedAnswer::selectRaw('sum(offered_answers.score)')
    //                     ->join('officer_answers','offered_answers.id','=','officer_answers.offered_answer_id')
    //                     ->join('officer_targets','officer_targets.target_id','=','officer_answers.target_id')
    //                     ->where('officer_targets.submited_at','<>',null)
    //                     ->whereColumn('officer_targets.target_id','=', 'targets.id')
    //                     ->where('targets.type','<>','responden')
    //             ])
    //             ->get();
    // $query_dump = DB::getQueryLog();
    // dd($query_dump); 
    dd($data);
});

