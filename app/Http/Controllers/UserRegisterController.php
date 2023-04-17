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
    public function userRegister(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'profil_picture' => 'sometimes|file|image|max:2048',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        } else {
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                if (Hash::check($request->input('password'), $user->password)) {
                    Auth::login($user);
                    $token = $user->createToken('authToken')->plainTextToken;
                    return response()->json([
                        'status' => 'success',
                        'token' => $token
                    ]);
                } else {
                    return response()->json([
                        'email' => 'Les informations de connexion sont incorrectes'
                    ], 422);
                }
            } else {
                return response()->json([
                    'email' => 'Les informations de connexion sont incorrectes'
                ], 422);
            }
        }
    }
}
