<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Enums\UserRoles;

class ForgotPasswordController extends Controller
{
    // ===== SHOW FORGOT PASSWORD FORM =====
    public function create()
    {
        return view('auth.forgot-password');
    }

    // ===== HANDLE OTP REQUEST =====
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        // 🔥 Only ADMIN allowed (SmartHospital rule)
        if ($user->role->value !== UserRoles::ADMIN->value) {
            return back()->withErrors(['email' => 'Unauthorized (Admin only)']);
        }

        // Generate OTP code
        $code = rand(100000, 999999);

        // Save OTP in DB
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'code' => $code,
                'otp' => $code, 
                'created_at' => now()
            ]
        );

        // Send email
        Mail::raw(
            "Your OTP code is: $code",
            function ($msg) use ($request) {
                $msg->to($request->email)
                    ->subject('Admin Password Reset Code');
            }
        );

        return redirect()
            ->route('verify.code')
            ->with('email', $request->email);
    }
}