<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ResetPasswordController extends Controller
{
    // ======================
    // SHOW RESET FORM
    // ======================
    public function create(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->email,
            'code'  => $request->code
        ]);
    }

    // ======================
    // RESET PASSWORD
    // ======================
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'code'     => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found']);
        }

        // 🔥 Admin-only rule (SmartHospital logic)
        if ($user->role !== \App\Enums\UserRoles::ADMIN->value) {
            return back()->withErrors(['email' => 'Unauthorized']);
        }

        // Check OTP
        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return back()->withErrors(['code' => 'Invalid code']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete OTP after use
        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        return redirect()
            ->route('login')
            ->with('status', 'Password updated successfully!');
    }
}
