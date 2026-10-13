<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\ValidTurnstileToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cf-turnstile-response' => config('services.turnstile.enabled')
                ? ['required', 'string', 'max:2048', new ValidTurnstileToken]
                : ['nullable', 'string', 'max:2048'],
        ]);

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            if ($existingUser->email_verified_at !== null) {
                throw ValidationException::withMessages([
                    'email' => trans('validation.unique', ['attribute' => 'email']),
                ]);
            }

            $existingUser->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
            $user = $existingUser;
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);
        }

        $code = (string) random_int(100000, 999999);
        $user->email_otp_code = $code;
        $user->email_otp_expires_at = now()->addMinutes(10);
        $user->save();

        OtpVerificationController::sendOtpEmail($user, $code, 'registration');

        session(['register_user_id' => $user->id]);

        return redirect()->route('register.otp');
    }
}
