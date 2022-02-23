<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\UserCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * login method will 
     * checks nb of attempts
     * validates fields, 
     * checks user credentials, 
     * generates verfication code by email
     * redirects to home if logged in
     *
     * @return response()
     */
    public function login(Request $request)
    {
        $this->checkTooManyFailedAttempts();

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            RateLimiter::clear($this->throttleKey());

            auth()->user()->generateCode();

            return redirect()->route('2fa.index');
        }

        // Increment user's number of attempts (limit is 5) and set the waiting time to 180 seconds
        RateLimiter::hit($this->throttleKey(), $seconds = 180);

        return redirect("login")->with('error', 'Wrong credentials ! Please try again.');
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     */
    public function checkTooManyFailedAttempts()
    {
        $maxNumberOfAttempts = 5;

        if (RateLimiter::tooManyAttempts($this->throttleKey(), $maxNumberOfAttempts)) {
            throw new Exception('IP address banned. Too many login attempts.');
            // return redirect("login")->with('attemptError', 'Too many attempts. Please try again later.');
        }

        return;
    }
}
