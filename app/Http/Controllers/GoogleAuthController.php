<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Create a new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'profile_image' => $googleUser->getAvatar(),
                ]);
            }

            // Log in the user
            Auth::login($user, true);

            // Redirect to the user's dashboard
            return response()->json([
                'okay' => 'welcome to your dashboard',
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue lors de la connexion avec Google',
            ], 422);
        }
    }
}
