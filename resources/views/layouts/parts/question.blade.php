@switch($question->questionType->name)
    @case('Singkat')
        @include('layouts.questions.'.$type.'.singkat',['number'=> $loop->iteration,'item' => $question])
        @break
    @case('Paraghraf')
        @include('layouts.questions.'.$type.'.paraghraf',['number'=> $loop->iteration,'item' => $question])
        @break
    @case('Pilihan Ganda')
        @include('layouts.questions.'.$type.'.ganda',['number'=> $loop->iteration,'item' => $question])
        @break
    @case('Kotak Centang')
        @include('layouts.questions.'.$type.'.checkbox',['number'=> $loop->iteration,'item' => $question])
        @break
    @case('Dropdown')
        @include('layouts.questions.'.$type.'.dropdown',['number'=> $loop->iteration,'item' => $question])
        @break
    @case('File Upload')
        @include('layouts.questions.'.$type.'.upload',['number'=> $loop->iteration,'item' => $question])
        @break
@endswitch