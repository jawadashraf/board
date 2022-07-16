<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:56'],
            'description' => ['nullable', 'string'],
            'column_id' => ['required', 'exists:columns,id']
        ]);

        return Card::create($request->only('title', 'description', 'column_id'));
    }

    public function update(Request $request)
    {
        $this->validate(request(), [
            'card' => ['required']
        ]);

        $card = $request->card;
        Card::find($card['id'])
            ->update(['title' => $card['title'], 'description' => $card['description']]);

        return Card::find($card['id']);
    }

    public function sync(Request $request)
    {
        $this->validate(request(), [
            'columns' => ['required', 'array']
        ]);

        foreach ($request->columns as $column) {
            foreach ($column['cards'] as $i => $card) {
                $order = $i + 1;
                if ($card['column_id'] !== $column['id'] || $card['order'] !== $order) {
                    Card::find($card['id'])
                        ->update(['column_id' => $column['id'], 'order' => $order]);
                }
            }
        }

        return Column::with('cards')->get();
    }
}
