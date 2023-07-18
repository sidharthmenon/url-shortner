<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as RulesPassword;

class LoginController extends Controller
{

  public function login_page(){
      return view('auth.login');
  }

  public function authenticate(Request $request){

    $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, true)) {
        $request->session()->regenerate();

        $user = Auth::user();

        return redirect()->intended(Route::has(explode('|', $user->roles[0]->type)[0])?route(explode('|', $user->roles[0]->type)[0]):'home');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function forgot_page(){
    return view('auth.forgot');
  }

  public function send_reset_link(Request $request){
    $request->validate([
        'email' => 'required|email|exists:users,email'
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    if($status === Password::RESET_LINK_SENT)
    {
        flash()->success('Reset Link Send');
    }
    else{
        flash()->error('Failed');
    }

    return back();
  }

  public function reset_page(){
    return view('auth.reset');
  }
  
  public function reset_password(Request $request){

    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => ['required', 'confirmed', RulesPassword::min(8)->mixedCase()->letters()->numbers()->symbols() ],
    ]);


    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) use ($request) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();
        }
    );

    if($status == Password::PASSWORD_RESET){
        flash()->success('Reset Successfull');

        Auth::logoutOtherDevices($request->input('password'));

        return redirect()->route('login');
    }
    else{
        flash()->error('Failed');
        return back();
    }

  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    // flash('Logged out successfully');
    flash()->success('Logged out successfully');

    if($request->redirect){
        return redirect($request->redirect);
    }
    else{
        return redirect()->route('login');
    }
  }

}
