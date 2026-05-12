<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use App\Enums\UserRoles;

class DoctorResetPasswordController extends Controller
{
    // =========================
    // CHECK ROLE (🔥 function reusable)
    // =========================
    private function checkAccess($user, $request)
    {
        $source = $request->header('source'); // mobile / null

        if ($source === 'mobile') {
            // 📱 Mobile → Doctor only
            if ($user->role->value !== UserRoles::DOCTOR->value) {
                return response()->json([
                    'message' => 'Unauthorized (Doctor only)'
                ], 403);
            }
        } else {
            // 💻 Web → Admin only
            if ($user->role->value !== UserRoles::ADMIN->value) {
                return response()->json([
                    'message' => 'Unauthorized (Admin only)'
                ], 403);
            }
        }

        return null;
    }

    // =========================
    // 1. SEND CODE (OTP)
    // =========================
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email not found'
            ], 404);
        }

        // 🔥 ROLE CHECK
        if ($response = $this->checkAccess($user, $request)) {
            return $response;
        }

        $code = rand(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'code' => $code,
                'created_at' => now()
            ]
        );

        Mail::raw("Your OTP code is: $code", function ($msg) use ($request) {
            $msg->to($request->email)
                ->subject('Password Reset Code');
        });

        return response()->json([
            'message' => 'Code sent successfully'
        ]);
    }

    // =========================
    // 2. VERIFY CODE
    // =========================
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required'
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Invalid code'
            ], 400);
        }

        // expire 10 min
        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            return response()->json([
                'message' => 'Code expired'
            ], 400);
        }

        return response()->json([
            'message' => 'Code verified'
        ]);
    }

    // =========================
    // 3. RESET PASSWORD
    // =========================
    public function resetWithCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // 🔥 ROLE CHECK
        if ($response = $this->checkAccess($user, $request)) {
            return $response;
        }

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Invalid code'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }
}
