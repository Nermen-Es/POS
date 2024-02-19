<?php
namespace App\Services;

use App\Http\Requests\ResendEmailVerificationRequest;
use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Notifications\EmailVerficationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class EmailVerificationService
{
    /**
     * Send verification link to user
     */
    public function sendVerificationLink(object $user): void
    {
        Notification::send($user, new EmailVerficationNotification($this->generateVerificationLink($user->email)));
    }

    /**
     * Resend link with token
     */
    public function resendLink(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->sendVerificationLink($user);
            return response()->json([
                'status' => 'success',
                'message' => 'Verification link send successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User not found'
            ]);
        }
    }

    /**
     * Check if user already been verified
     */
    public function checkIfEmailIsVerified(object $user)
    {
        if ($user->email_verified_at ) {
            response()->json([
                'status' => 'failed',
                'message' => 'Email has already been verified'
            ])->send();
            exit;
        }
    }


    /**
     * Verify user email
     */
    public function verifyEmail(string $email, string $token)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            response()->json([
                'status' => 'failed',
                'message' => 'User not found'
            ])->send();
            exit;
        }

        $this->checkIfEmailIsVerified($user);
        $verifyToken = $this->verifyToken($email, $token);
        if($user->markEmailAsVerified()) {
            $verifyToken->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Email has been verified'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Email; verification failed, please try again later'
            ]);
        }
    }

    /**
     * Verify token
     */
    public function verifyToken(string $email, string $token)
    {
        $token = EmailVerificationToken::where('email', $email)->where('token', $token)->first();
        if($token) {
            if($token->expired_at >= now()) {
                return $token;
            } else {
                $token->delete();
                response()->json([
                    'status' => 'failed',
                    'message' => 'Token expired'
                ])->send();
                exit;
            }                
        } else {
            response()->json([
                'status' => 'failed',
                'message' => 'Invalid token'
            ])->send();
            exit;
        }
    }

    /**
     * Generate verification link
     *
     * @return string
     */
    public function generateVerificationLink(string $email): string
    {
        $checkIfTokenExists = EmailVerificationToken::where('email', $email)->first();
        
        if($checkIfTokenExists) $checkIfTokenExists->delete();

        $token = Str::uuid();

        $url = config('app.url') . "?token=" . $token . "?email=" . $email;

        $saveToken = EmailVerificationToken::create([
            "email" => $email,
            "token" => $token,
            "expired_at" => now()->addMinutes(60),
        ]);

        if($saveToken) {
            return $url;
        }
    }
}
