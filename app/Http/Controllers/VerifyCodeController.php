<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerifyCodeController extends Controller
{
    public function verifyCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required'
    ]);

    $record = DB::table('password_resets')
    ->where('email', $request->email)
    ->where(function ($q) use ($request) {
        $q->where('code', $request->code)
          ->orWhere('otp', $request->code);
    })
    ->first();

    if (!$record) {
        return back()->withErrors(['code' => 'Invalid code']);
    }

    // expire 10 min
    if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
        return back()->withErrors(['code' => 'Code expired']);
    }

    // 🔥 success → go to reset page
    return redirect()->route('reset.password.form', [
        'email' => $request->email,
        'code' => $request->code
    ]);
}
}