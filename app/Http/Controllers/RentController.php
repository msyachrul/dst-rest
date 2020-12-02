<?php

namespace App\Http\Controllers;

use App\Book;
use App\Rent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['jwt']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rents = Rent::with(['user'])->paginate();

        return response()->json($rents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'books' => [
                'required',
                'array',
            ],
            'books.*' => [
                'required',
                'exists:books,id',
            ],
        ]);

        $rentedBooks = Book::whereIn('id', $request->books)->whereNotNull('last_position')->get();

        if ($rentedBooks->count()) {
            throw ValidationException::withMessages(['books' => 'One or more books already rented.']);
        }

        DB::beginTransaction();

        $price = count($request->books) * 5000 * 1.1;

        $rent = Rent::create([
            'user_id' => $request->user_id,
            'price' => $price,
        ]);

        $rented_books = collect($request->books)
            ->map(function ($book_id) {
                return ['book_id' => $book_id];
            })
            ->all();

        $rent->rented_books()->createMany($rented_books);

        Book::whereIn('id', $request->books)->update(['last_position' => $request->user_id]);

        DB::commit();

        return response()->json($rent, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function show(Rent $rent)
    {
        $rent->load(['rented_books.book']);

        return response()->json($rent);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rent $rent)
    {
        DB::beginTransaction();

        $rent->rented_books()->delete();
        $rent->delete();

        DB::commit();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
