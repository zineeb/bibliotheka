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
        $userId = Auth::id();
        $booksUser = Book::allBooks($userId);

        return response()->json([
            'books_user' => $booksUser
        ]);
    }
}
