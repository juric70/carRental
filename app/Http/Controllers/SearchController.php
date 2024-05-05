<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State; // Ili bilo koji drugi model koji pretražujete

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = State::where('name', 'LIKE', "%{$query}%")->get();
        return response()->json($results);
    }
}
