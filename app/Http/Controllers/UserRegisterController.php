<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserRegisterController extends Controller
{
    public function userRegister(Request $request)
    {
        //Verification of the correct registration of the data by the user
        $validateData = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'g-recaptcha-response' => ['required', new ReCaptcha()],
            'profil_picture' => 'sometimes|file|image|max:2048',
        ]);
        //If the data is wrong, return to the registration page with the errors made
        if ($validateData->fails()) {
            //TODO : lien vers la page d'inscription dans Vue.js
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        } else {
            //Password Hash
            $password = Hash::make($request['password']);
            //Variable that will contain the profile picture accorting to what the user has put in his registration
            if ($request->hasFile('profil_picture')) {
                $file = $request->file('profil_picture');
                $fileName = time() . $file->getClientOriginalName();
                $file->move(public_path('images/profil_pictures'), $fileName);
                $path = 'images/profil_pictures/' . $fileName;
            } else {
                $path = 'images/utilisateur.png';
            }
            //Creation of the user in the database
            User::create([
                'name' => $request['username'],
                'email' => $request['email'],
                'password' => $password,
                'profile_image' => $path,
            ]);

            //Redirection to the user's personal page view
            //TODO : lien vers la page dashboard dans Vue.js
            return response()->json([
                'okay' => 'welcome to your dashboard'
            ], 200);
        }
    }
}
