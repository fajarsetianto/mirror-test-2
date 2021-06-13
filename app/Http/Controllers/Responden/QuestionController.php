<?php

namespace App\Http\Controllers\Responden;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Crypt;

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
            'url' => route('respondent.form.question.store', [$instrument->id])
        ]);
    }

    public function store(Request $request, Instrument $instrument)
    {
        $data   = $request->all();
        $userId = auth('respondent')->user()->id;
        foreach($instrument->questions()->get() as $key => $row):
            UserAnswer::where('question_id', $row->id)->delete();
            if($row->question_type_id == '1' || $row->question_type_id == '2'):
                $userAnswer = new UserAnswer(array(
                    'answer' => $data["answer_$key"],
                    'offered_answer_id' => NULL,
                    'question_id' => $row->id,
                    'respondent_id' => $userId
                ));
            elseif ($row->question_type_id == '6'):
                $file = $request->file("file_$key");
                $fileName = time().'-'.$userId.'-'.$file->getClientOriginalName();
                $userAnswer = new UserAnswer(array(
                    'answer' => $fileName,
                    'offered_answer_id' => NULL,
                    'question_id' => $row->id,
                    'respondent_id' => $userId
                ));
                $file->move("data_file",$fileName);
            else:
                $answer = explode("__", $data["answer_option_$key"]);
                $userAnswer = new UserAnswer(array(
                    'answer' => $answer[0],
                    'offered_answer_id' => $answer[1],
                    'question_id' => $row->id,
                    'respondent_id' => $userId
                ));
            endif;
            $userAnswer->save();
        endforeach;

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
