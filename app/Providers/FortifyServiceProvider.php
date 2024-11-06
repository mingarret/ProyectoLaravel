<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Fortify::loginView(function () {
            return view('auth.login'); // Vista personalizada de login
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge'); // Vista personalizada para el desafío de MFA
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }

}
