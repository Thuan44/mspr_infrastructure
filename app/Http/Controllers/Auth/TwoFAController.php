<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCode;
use Illuminate\Support\Facades\Session;

class TwoFAController extends Controller
{
    /**
     * Return the view '2fa'
     *
     * @return response()
     */
    public function index()
    {
        return view('2fa');
    }

    /**
     * After entering the received code, redirect to home or send error message 
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $find = UserCode::where('user_id', auth()->user()->id)
            ->where('code', $request->code)
            ->where('updated_at', '>=', now()->subMinutes(2))
            ->first();

        if (!is_null($find)) {
            Session::put('user_2fa', auth()->user()->id);
            return redirect()->route('home');
        }

        return back()->with('error', 'You entered wrong code.');
    }

    /**
     * Re-generate code
     *
     * @return response()
     */
    public function resend()
    {
        auth()->user()->generateCode();

        return back()->with('success', 'We sent you code on your email.');
    }
}
