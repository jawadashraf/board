<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:56'],
        ]);

        return Column::create($request->only('title'));
    }

    public function delete($id)
    {
        Column::destroy($id);
        return response()->json("ok");
    }
}
