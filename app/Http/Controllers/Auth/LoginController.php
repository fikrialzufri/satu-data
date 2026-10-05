<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
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
    protected $redirectTo = '/';

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function findUsername()
    {
        $login = request()->input('username');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        $messages = [
            'username.exists' => 'nik atau username tidak terdaftar',
            'g-recaptcha-response.required' => 'Verifikasi keamanan gagal. Silakan coba lagi.',
        ];

        $rules = [
            'username' => 'string|exists:users',
        ];

        $secretKey = config('services.recaptcha.secret_key');

        if (!empty($secretKey)) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $request->validate($rules, $messages);

        if (!empty($secretKey)) {
            $this->validateRecaptcha($request, $secretKey);
        }
    }

    /**
     * Validate the reCAPTCHA v3 token with Google's verification endpoint.
     */
    protected function validateRecaptcha(Request $request, string $secretKey): void
    {
        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secretKey,
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]);
        } catch (ConnectionException $exception) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi keamanan tidak dapat dilakukan. Silakan coba lagi.',
            ]);
        }

        $result = $response->json();
        $isValid = $response->successful()
            && ($result['success'] ?? false) === true
            && ($result['action'] ?? null) === 'login'
            && (float) ($result['score'] ?? 0) >= (float) config('services.recaptcha.score_threshold', 0.5);

        if (!$isValid) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi keamanan gagal. Silakan coba lagi.',
            ]);
        }
    }
}
