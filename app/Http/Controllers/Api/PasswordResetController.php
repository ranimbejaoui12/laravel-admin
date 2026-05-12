<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class PasswordResetController extends Controller
{
    // ================= SEND OTP =================
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Patient أو Doctor
        $user = User::where('email', $request->email)
            ->whereIn('role', [
                \App\Enums\UserRoles::PATIENT,
                \App\Enums\UserRoles::DOCTOR
            ])
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $otp = rand(100000, 999999);
        $token = Str::random(60);

        // تخزين OTP + token
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'otp' => $otp,
                'code' => $otp, // Flutter يستعمل code
                'created_at' => Carbon::now()
            ]
        );

        // إرسال OTP عبر الإيميل
        Mail::to($user->email)->send(
            new ResetPasswordMail($otp, $user->email)
        );

        // Debug
        \Log::info("OTP for {$user->email}: {$otp}");

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'token' => $token
        ]);
    }

    // ================= VERIFY OTP =================
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required'
        ]);

        // Flutter يبعث code أما DB فيها otp
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('otp', $request->code)
            ->first();

        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid code'
            ], 400);
        }

        // صلاحية 10 دقائق
        if (
            Carbon::parse($reset->created_at)
                ->addMinutes(10)
                ->isPast()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Code expired'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code verified successfully'
        ]);
    }

    // ================= RESET PASSWORD =================
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        // Flutter يبعث code
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('otp', $request->code)
            ->first();

        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid code'
            ], 400);
        }

        // Patient أو Doctor
        $user = User::where('email', $request->email)
            ->whereIn('role', [
                \App\Enums\UserRoles::PATIENT,
                \App\Enums\UserRoles::DOCTOR
            ])
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // تحديث كلمة السر
        $user->password = bcrypt($request->password);
        $user->save();

        // حذف OTP بعد النجاح
        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    // ================= RESEND OTP =================
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return $this->sendOtp($request);
    }
}