<?php

namespace App\Http\Controllers;

use App\RentedBook;
use Illuminate\Http\Request;

class RentedBooks extends Controller
{
    public function __construct()
    {
        $this->middleware(['jwt']);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // $bookHistories = RentedBook::join('books', 'rented_books.book_id', '=', 'books.id')
        //     ->join('rents', 'rented_books.rent_id', '=', 'rents.id')
        //     ->join('users', 'rents.user_id', '=', 'users.id')
        //     ->select([
        //         'rented_books.id',
        //         'rented_books.book_id',
        //         'books.title',
        //         'books.description',
        //         'users.name as last_position',
        //         'rented_books.created_at as rent_time',
        //     ])
        //     ->paginate();

        $bookHistories = RentedBook::with(['rent.user', 'book'])->paginate();

        return response()->json($bookHistories);
    }
}
