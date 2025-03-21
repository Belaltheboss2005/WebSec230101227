<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ForgetPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('users.ForgetPasswordEmail'); // Load the view for password reset
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Log the request to send a reset link
        Log::info('Password reset link requested for email: ' . $request->email);

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        // Log the status of the reset link email
        if ($status === Password::RESET_LINK_SENT) {
            Log::info('Password reset link sent successfully to email: ' . $request->email);
        } else {
            Log::error('Failed to send password reset link to email: ' . $request->email);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm($token)
    {
        return view('users.resetPassword', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            Log::info('Password reset successfully for email: ' . $request->email);
            return redirect()->route('login')->with('status', __($status));
        } else {
            Log::error('Failed to reset password for email: ' . $request->email);
            return back()->withErrors(['email' => [__($status)]]);
        }
    }
}
