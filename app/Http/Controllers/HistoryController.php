<?php

namespace App\Http\Controllers;

use App\Http\Resources\HistoryResource;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        return History::paginate(10);
    }

    public function store(Request $request)
    {

    }

    public function show(History $history)
    {
        return new HistoryResource($history);
    }
}
