<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

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
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->is_admin) {
            return '/mex-admin/dashboard';
        }

        if ($user->hasRole('instructor')) {
            return '/instructor/dashboard';
        }

        if ($user->hasRole('student')) {
            return '/student/dashboard';
        }

        return '/dashboard'; // fallback
    }

    public function ajaxLogin(Request $request)
    {
        // Validate inputs + recaptcha token string
        $data = $request->validate([
            'email'                 => ['required','email'],
            'password'              => ['required','string'],
            'remember'              => ['nullable','boolean'],
            'g-recaptcha-response'  => ['required','string'],
        ]);

        // Verify reCAPTCHA v3
        $rc = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('recaptcha.secret_key'),
            'response' => $data['g-recaptcha-response'],
            'remoteip' => $request->ip(),
        ])->json();

        if (!($rc['success'] ?? false) || ($rc['score'] ?? 0) < 0.5 || ($rc['action'] ?? '') !== 'login') {
            return response()->json([
                'message' => 'Captcha verification failed.',
                'errors'  => ['captcha' => ['Captcha verification failed.']],
            ], 422);
        }

        // Attempt login
        if (! Auth::attempt(
            ['email' => $data['email'], 'password' => $data['password']],
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        $request->session()->regenerate();

        return response()->json(['ok' => true]);
    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
