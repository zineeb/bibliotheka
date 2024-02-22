<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Notifications\ContactNotification;
use App\Notifications\CustomResetPassword;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserConnectionController extends Controller
{
    /**
     * This function handles user authentication and registration.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

                    $token = $user->createToken('authToken')->plainTextToken;

                    $booksUser = Book::allBooks($user->id);
                    $infosUser = User::allInformations($user->id);

                    return response()->json([
                        'status' => 'success',
                        'token' => $token,
                        'infos_user' => $infosUser,
                        'books_user' => $booksUser,
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

    /**
     * This function handles the forgot password process for users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
                $user->notify(new CustomResetPassword($token, $user->email));
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

    /**
     * This function displays the reset password form for users.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showResetPasswordForm(Request $request)
    {
        // Retrieve the token and email from the request input.
        $token = $request->input('token');
        $email = $request->input('email');

        // Return the 'resetPassword' view with the token and email variables.
        return view('resetPassword', ['token' => $token, 'email' => $email]);
    }


    /**
     *  This function handles the password reset process for users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    public function checkAuth(Request $request) {
        return response()->json(['authenticated' => Auth::check()]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ];

        Notification::route('mail', 'mouazzazz@gmail.com')->notify(new ContactNotification($details));
        return response()->json(['message' => 'Email sent successfully']);
    }

}
