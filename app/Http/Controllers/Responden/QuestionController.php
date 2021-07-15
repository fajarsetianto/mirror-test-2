<?php

namespace App\Http\Controllers\Responden;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\UserAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{

    protected $viewNamespace = 'pages.responden.';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:respondent');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Instrument $instrument)
    {
        $user = auth('respondent')->user()->load('target.form');
        return view($this->viewNamespace.'question', [
            'form' => $user->target->form,
            'instrument' => $instrument,
            'user' => $user,
            'url' => route('respondent.form.question.store', [$instrument->id])
        ]);
    }

    public function store(Request $request, Instrument $instrument)
    {
        $data   = $request->all();
        $userId = auth('respondent')->user()->id;
        try{
            DB::beginTransaction();
            $arr = array();
            foreach($instrument->questions()->get() as $key => $row):                
                if($row->question_type_id == '1' || $row->question_type_id == '2'):
                    UserAnswer::updateOrCreate(
                        ['question_id'=> $row->id, 'respondent_id'=> $userId],
                        [
                            'answer' => $data["answer_$key"],
                            'offered_answer_id' => NULL,
                            'question_id' => $row->id,
                            'respondent_id' => $userId
                        ]
                    );
                   
                elseif ($row->question_type_id == '6'):
                    if(array_key_exists("file_$key", $data)):
                        $file = $request->file("file_$key");
                        $fileName = time().'-'.$userId.'-'.$file->getClientOriginalName();
                        UserAnswer::updateOrCreate(
                            ['question_id'=> $row->id, 'respondent_id' => $userId],
                            [
                                'answer' => $fileName,
                                'offered_answer_id' => NULL,
                                'question_id' => $row->id,
                                'respondent_id' => $userId
                            ]
                        );
                        $file->move("data_file",$fileName);
                    endif;
                elseif($row->question_type_id == '4'):
                    UserAnswer::where([
                        ['question_id', $row->id],
                        ['respondent_id', $userId]
                    ])->delete();
                    foreach($row->offeredAnswer()->get() as $nm => $checkbox):
                        if(array_key_exists("answer_option_".$key."_".$nm, $data)):
                            $answer = explode("__", $data["answer_option_".$key."_".($nm)]);
                            UserAnswer::updateOrCreate(
                                ['question_id'=> $row->id, 'respondent_id'=> $userId, 'offered_answer_id' => $answer[1]],
                                [
                                    'answer' => $answer[0],
                                    'offered_answer_id' => $answer[1],
                                    'question_id' => $row->id,
                                    'respondent_id' => $userId
                                ]
                            );
                        endif;
                    endforeach;
                else:
                    $answer = explode("__", $data["answer_option_$key"]);
                    UserAnswer::updateOrCreate(
                        ['question_id'=> $row->id, 'respondent_id'=> $userId],
                        [
                            'answer' => $answer[0],
                            'offered_answer_id' => $answer[1],
                            'question_id' => $row->id,
                            'respondent_id' => $userId
                        ]
                    );
                endif;
                if(array_key_exists("option_other_".$row->id, $data)):
                    if(empty($data["option_other_".$row->id])):
                        UserAnswer::where([
                            ['question_id', $row->id],
                            ['respondent_id', $userId],
                            ['offered_answer_id', NULL]
                        ])->delete();
                    else:
                        UserAnswer::updateOrCreate(
                            ['question_id'=> $row->id, 'respondent_id'=> $userId, 'offered_answer_id' => NULL],
                            [
                                'answer' => $data["option_other_".$row->id],
                                'offered_answer_id' => NULL,
                                'question_id' => $row->id,
                                'respondent_id' => $userId
                            ]
                        );
                    endif;
                endif;
            endforeach;

            $responden = auth('respondent')->user();
            if($responden->start_working_at == null){
                $responden->update([
                    'start_working_at' => Carbon::now()
                ]);
            }

            DB::commit();
        } catch(\Throwable $throwable){
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'title' => 'Failed!',
                'msg' => 'Data failed Updated!'.$throwable->getMessage()
            ],422);
        }


        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Updated!'
        ],200);
    }

    public function show(Request $request, Instrument $instrument){
        $file = $request->get('file');
        $fileName = UserAnswer::where([
            ['answer','like',"%$file%"],
            ['respondent_id', auth('respondent')->user()->id]
        ])->first();
        $filePath = public_path('data_file/'.$fileName->answer);
        return response()->download($filePath, $file);
    }
}
