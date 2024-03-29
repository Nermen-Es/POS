<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Services\EmailVerificationService;
use Illuminate\Support\Facades\{Auth,Hash};

class AuthController extends Controller
{
    public function __construct(private EmailVerificationService $emailVerificationService)
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }


        $user = Auth::user();
        $this->emailVerificationService->sendVerificationLink($user);
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Resend verification link
     */
    public function resendEmailVerificationLink(ResendEmailVerificationRequest $request)
    {
        return $this->emailVerificationService->resendLink($request->email);
    }

    /**
     * Verify user email
     */
    public function verifyUserEmail(VerifyEmailRequest $request) {
        return $this->emailVerificationService->verifyEmail($request->email, $request->token);
    }

    //logut
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    //reset password useing Password Facades
    public function changePassword(Request $request){

        $user = Auth::user();
        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');

        if (Hash::check($currentPassword, $user->password)) {
            $user->password = Hash::make($newPassword);
            $user->save();

            return response()->json(['message' => 'Password changed successfully'], 200);
        } else {
            return response()->json(['message' => 'Invalid current password'], 401);
        }
    }

}
