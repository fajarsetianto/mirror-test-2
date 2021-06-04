<?php

namespace App\Http\Controllers\Responden\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = route('respondent.dashboard');
        $this->middleware('guest:respondent')->except('logout','checkpoint','checkpointStore');
    }

    public function showLoginForm()
    {
        return view('pages.responden.auth.login');
    }
 
    protected function guard()
    {
        return Auth::guard('respondent');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);
    }

    protected function credentials(Request $request)
    {
        $request->merge([
            'plain_token' => $request->token,
            'password' => $request->token
        ]);
        return $request->only('plain_token','password');
    }
    
    public function username()
    {
        return 'plain_token';
    }

    protected function attemptLogin(Request $request)
    {    
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('respondent.login');
    }

    public function checkpoint(){
        return view('pages.responden.auth.checkpoint');
    }

    public function checkpointStore(Request $request){
        $request->validate([
            'name' => 'required|string'
        ]);

        auth('respondent')->user()->update($request->only('name'));

        return redirect()->route('respondent.dashboard');
    }

}
