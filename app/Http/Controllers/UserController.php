<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * This function retrieves and returns user information based on the provided user ID.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $user = User::allInformations($id);

        return response()->json($user);
    }

    /**
     * This function updates the user information based on the provided user ID and request data.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the request data.
        $validatedData = $request->validate([
            'name' => 'sometimes|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|min:8'
        ]);

        // If a password is provided, hash it before updating the user.
        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);
        return response()->json($user);
    }

    public function updateProfileImage(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->hasFile('profile_image')) {
            Log::info('profile image changed :');
            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profil_pictures/'), $filename);
            $profilePicture = 'images/profil_pictures/' . $filename;
            $user->profile_image = $profilePicture;
            $user->save();
        }

        return response()->json(['status' => 200]);
    }

    /**
     * This function deletes a user based on the provided user ID.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        Review::where('user_id', $id)->delete();
        Status::where('user_id',$id)->delete();

        $user->delete();

        return response()->json(['status' => 200]);
    }
}
