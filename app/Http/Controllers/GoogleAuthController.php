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

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'profile_image' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user);
            $token = $user->createToken('authToken')->plainTextToken;

            $cookie = cookie('auth_token', $token, 60, null, null, true, true);
            return redirect('/dashboard')->withCookie($cookie);

        } catch (\Exception $e) {
            Log::error('Exception levée lors de handleGoogleCallback: ' . $e->getMessage());

            return response()->json([
                'error' => 'Une erreur est survenue lors de la connexion avec Google',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

}
//$cookie = cookie('auth_token', $token, 60, null, null, null, true);
//
//            return redirect()->away(env('VUE_APP_URL') . '/dashboard')->withCookie($cookie);
