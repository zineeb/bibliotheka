<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;


class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

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
            Auth::login($user);
            $token = $user->createToken('authToken')->plainTextToken;

            // Return a view with a script that sends a message to the parent window
            return view('auth.google_callback', ['token' => $token]);

        } catch (\Exception $e) {
            // Ajoutez ce log
            Log::error('Exception levée lors de handleGoogleCallback: ' . $e->getMessage());

            return response()->json([
                'error' => 'Une erreur est survenue lors de la connexion avec Google',
                'message' => $e->getTraceAsString(),
            ], 422);
        }
    }

}
