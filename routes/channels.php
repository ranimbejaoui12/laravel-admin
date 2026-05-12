<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
| Registered channels for private notifications (doctor system)
|--------------------------------------------------------------------------
*/

/**
 * Default Laravel user channel (optional)
 */
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


/**
 * Doctor private notifications channel (MAIN FIX)
 *
 * This channel is used for:
 * - Attestation notifications
 * - Real-time updates (Pusher / WebSockets)
 * - Flutter subscription: doctor.{doctorId}
 */
Broadcast::channel('doctor.{id}', function ($user, $id) {

    // ✅ CASE 1: if doctor = user (simple apps)
    // return (int) $user->id === (int) $id;

    // ✅ CASE 2: BEST PRACTICE (doctor relation exists)
    if (isset($user->doctor)) {
        return (int) $user->doctor->id === (int) $id;
    }

    // ✅ CASE 3: fallback if doctor_id stored in users table
    if (isset($user->doctor_id)) {
        return (int) $user->doctor_id === (int) $id;
    }

    return false;
});