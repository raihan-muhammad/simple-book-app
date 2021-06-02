<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Book::all();

        return view('pages.book', ['data' => $data]);
    }

    /**
     * Display a detail of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Book::with(['authors' => function ($q) {
            $q->select(['authors.id', DB::raw('CONCAT(first_name, " " , middle_name, " ", last_name) as text')]);
        }])->find($id);

        return response()->json([
            'message' => "Data Berhasil Didapatkan.",
            'data' => $data,
        ], 200);
    }

    protected function requestForm()
    {
        $form = request()->validate([
            'title' => 'string|required',
            'total_pages' => 'integer|required',
            'rating' => 'integer|required',
            'isbn' => 'string|required',
            'published_date' => 'string|required',
            'author' => 'array'
        ]);
        return $form;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->requestForm();
        $action = Book::create($data);
        $action->authors()->attach($data['author']);

        if (!$action) return App::abort(400);

        return response()->json([
            'message' => "Data Berhasil Disimpan.",
            'data' => $data,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->requestForm();
        $action = Book::find($id);
        $action->update($data);
        $action->authors()->sync($data['author']);
        if (!$action) return App::abort(400);

        return response()->json([
            'message' => "Data Berhasil Diubah.",
            'data' => $action,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action = Book::find($id);
        $action->delete();
        $action->authors()->detach($id);
        if (!$action) return App::abort(400);

        return response()->json([
            'message' => "Data Berhasil Dihapus.",
            'data' => $action,
        ], 200);
    }
}