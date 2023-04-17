<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * This function is used to retrieve dashboard data for the currently authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardData()
    {
        // Get the ID of the currently authenticated user.
        $userId = Auth::id();
        // Retrieve all books associated with the user using a custom function in the Book model.
        $booksUser = Book::allBooks($userId);
        // Retrieve all user-related information using a custom function in the User model.
        $infosUser = User::allInformations($userId);

        // Return a JSON response containing both the user information and the list of their books.
        return response()->json([
            'infos_user' => $infosUser,
            'books_user' => $booksUser
        ]);
    }
}
