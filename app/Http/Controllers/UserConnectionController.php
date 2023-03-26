<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserConnectionController extends Controller
{
    public function connectRegister(Request $request)
    {
        //Verification of the correct connection of the data by the user
        $validateData = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);
        //If the data is wrong, return to the connection page with the errors made
        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        } else {
            //Verification of the existence of the user
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                //Verification of the password against the hashed password in the database
                if (Hash::check($request->input('password'), $user->password)) {
                    //User login
                    Auth::login($user);

                    $booksUser = Book::allBooks($user->id);
                    $infosUser = User::allInformations($user->id);

                    return response()->json([
                        'okay' => 'welcome to your dashboard',
                        'books user' => $booksUser,
                        'infos user' => $infosUser,
                    ], 200);
                } else {
                    //Redirection to the login view with the realized error
                    return response()->json([
                        'email' => 'Les informations de connexion sont incorrectes'
                    ], 422);
                }
            } else {
                //Redirection to the login view with the realized error
                return response()->json([
                    'email' => 'Les informations de connexion sont incorrectes'
                ], 422);

            }
        }
    }

    public function forgotPassword(Request $request)
    {
        // Verification of the correct connection of the data by the user
        $validateData = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255'
        ]);
        // If the data is wrong, return to the connection page with the errors made
        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        } else {
            //Verification of the existence of the user
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                //Creation of a token
                $token = Str::random(80);
                //Insertion of the token and the email of the user on the password_resets table
                DB::table('password_resets')->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                //Send an email for the link to the password reset page
                $user->notify(new ResetPassword($token));
                //Redirection to the forgotten password page with a success
                return response()->json([
                    'success' => 'Un email de réinitialisation de mot de passe a été envoyé.',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Aucun compte n\'est associé à cette adresse email.',
                ], 500);
            }
        }
    }


    public function resetPassword(Request $request)
    {
        //Verification of the correct connection of the data by the user
        $validateData = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'token' => 'required|string'
        ]);
        //If the data is wrong, return to the connection page with the errors made
        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors()
            ], 422);
        }
        //Changing the password with the new password entered by the user
        $user = User::where('email', $request['email'])->first();
        $user->password = Hash::make($request['password']);
        $user->save();

        //Deletion in the password_resets table of the user's email and token
        DB::table('password_resets')->where(['email' => $request['email'], 'token' => $request['token']])->delete();
        //Redirection to the login page
        return response()->json([
            'success' => 'Votre mot de passe a été mis à jour',
        ], 200);

    }
}
