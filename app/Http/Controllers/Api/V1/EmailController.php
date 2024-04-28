<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\ResetPasswordMailer;
use App\Mail\VerificationMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        // Get the email address from the request
        $email = $request->input('email');

        // Find the user by email
        $user = User::where('email', $email)->first();

        // If user doesn't exist, return an error
        if (!$user) {
            return response()->json([
                'status' => 'success',
                "message" => "User not found"
            ], 404);
        }

        // Generate verification code
        $verificationCode = $this->generateVerificationCode();

        // Update verification code in the database
        $user->verification_code = $verificationCode;
        $user->save();
        
        // Send email with the verification code
        Mail::to($email)->send(new VerificationMailer($verificationCode));

        return response()->json([
            'status' => 'success',
            "message" => "Email Sent"
        ], 200);
    }

    public function sendResetPasswordEmail(Request $request)
    {
        // Get the email address from the request
        $email = $request->input('email');

        // Find the user by email
        $user = User::where('email', $email)->first();

        // If user doesn't exist, return an error
        if (!$user) {
            return response()->json(["error" => "User not found"], 404);
        }

        // Generate reset code
        $verificationCode = $this->generateVerificationCode();

        // Update reset code in the database
        $user->verification_code = $verificationCode;
        $user->save();

        // Send email with the reset code
        Mail::to($email)->send(new ResetPasswordMailer($verificationCode));

        return response()->json(["message" => "Email Sent"], 200);
    }

    private function generateVerificationCode()
    {
        // Generate a random verification code, e.g., using Laravel's Str::random() method
        return \Illuminate\Support\Str::random(6); // Generates a 6-character random string
    }
}
