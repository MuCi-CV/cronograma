<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemUpdate;
use App\Models\Section;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'completed' => 'required|boolean',
            'comment'   => 'nullable|string|max:1000',
        ]);

        ItemUpdate::create([
            'item_id'   => $item->id,
            'user_id'   => auth()->id(),
            'completed' => $validated['completed'],
            'comment'   => $validated['comment'] ?? null,
        ]);

        return back();
    }

    public function store(Request $request, Section $section)
    {
        $validated = $request->validate([
            'type' => 'required|in:objetivo,meta',
            'text' => 'required|string|max:500',
        ]);

        $maxOrder = $section->items()->max('order') ?? 0;

        $section->items()->create([
            'type'   => $validated['type'],
            'text'   => $validated['text'],
            'order'  => $maxOrder + 1,
            'active' => true,
        ]);

        return back();
    }
}
