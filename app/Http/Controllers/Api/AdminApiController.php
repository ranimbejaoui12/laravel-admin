<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRoles;

class AdminApiController extends Controller
{
    public function pendingDoctors()
    {
        return User::where('role', UserRoles::DOCTOR)
                ->where('status', 'pending')
                ->get();
    }
    public function acceptDoctor($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'accepted';
        $user->save();

        return redirect()->back()->with('success', 'Doctor accepted successfully');
    }
    public function rejectDoctor($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        return redirect()->back()->with('success', 'Doctor rejected successfully');
    }
}
