<?php

namespace App\Http\Controllers;


use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserRegisterController extends Controller
{
    /**
     * This function handles user registration.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRegister(Request $request)
    {
        // Validate the user registration input data.
        $validateData = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'profil_picture' => 'sometimes|file|image|max:2048',
        ]);

        // If the validation fails, return an error response.
        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        } else {
            // Check if the user with the given email already exists.
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                // If the user exists and the password matches, log in the user.
                if (Hash::check($request->input('password'), $user->password)) {
                    Auth::login($user);
                    // Create an authentication token for the user.
                    $token = $user->createToken('authToken')->plainTextToken;
                    // Return a success response with the authentication token.
                    return response()->json([
                        'status' => 'success',
                        'token' => $token
                    ]);
                } else {
                    // Return an error response if the login information is incorrect.
                    return response()->json([
                        'email' => 'Les informations de connexion sont incorrectes'
                    ], 422);
                }
            } else {
                // Return an error response if the login information is incorrect.
                return response()->json([
                    'email' => 'Les informations de connexion sont incorrectes'
                ], 422);
            }
        }
    }
}
