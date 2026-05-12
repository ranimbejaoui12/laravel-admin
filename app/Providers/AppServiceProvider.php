<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Appointment;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // 🔹 Global variable for views
        View::composer('*', function ($view) {
            $newAppointmentsCount = Appointment::where('is_new', 1)->count();
            $view->with('newAppointmentsCount', $newAppointmentsCount);
        });

        // 🔹 Custom reset password URL (mobile deep link)
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return "myapp://reset-password?token=" . $token . "&email=" . $user->email;
        });

        // 🔹 Fix MySQL (optional)
        Schema::defaultStringLength(191);
    }
}