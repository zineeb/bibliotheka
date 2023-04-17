<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboardData()
    {
        $userId = Auth::id();
        $booksUser = Book::allBooks($userId);
        $infosUser = User::allInformations($userId);

        return response()->json([
            'infos_user' => $infosUser,
            'books_user' => $booksUser
        ]);
    }
}
