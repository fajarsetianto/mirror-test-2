<?php

namespace App\Http\Controllers\Responden;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
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
    public function index()
    {
        $user = auth('respondent')->user()->load('target.form');
        
        return view($this->viewNamespace.'dashboard', compact('user'));
    }
}
