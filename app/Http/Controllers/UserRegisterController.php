<?php

namespace App\Http\Controllers;


use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserRegisterController extends Controller
{
    /**
     * This function handles user registration.
     *
     * @param Request $request
     * @return mixed
     */
    public function userRegister(Request $request)
    {
        // Validate the user registration input data.
        $validateData = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'password_verification' => 'required|same:password',
            'profil_picture' => 'nullable|image|max:2048',
        ]);

        // If the validation fails, return an error response.
        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        } else {
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                return response()->json([
                    'email' => 'Un utilisateur avec cet email existe déjà'
                ], 422);
            } else {
                $profile_picture = 'images/utilisateur.png';
                if ($request->hasFile('profil_picture')) {
                    $file = $request->file('profil_picture');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/profil_pictures/'), $filename);
                    $profile_picture = 'images/profil_pictures/' . $filename;
                }

                $user = User::create([
                    'name' => $request->input('username'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'profile_image' => $profile_picture,
                ]);

                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'token' => $token,
                    'userId' => $user->id
                ]);
            }
        }
    }
}

