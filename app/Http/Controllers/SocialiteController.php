<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function github()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubRedirect()
    {
        $user = Socialite::driver('github')->user();

        $user = User::firstOrCreate(
            [
                'email' => $user->email,
            ],
            [
                'name' => $user->name,
                'password' => Hash::make(Str::random(24))
            ]
        );
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
