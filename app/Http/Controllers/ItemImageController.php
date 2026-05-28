<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemImageController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->extension();
        $path = $file->storeAs('items/' . $item->id, $filename, 'public');

        $maxOrder = $item->images()->max('order') ?? 0;

        $item->images()->create([
            'path'  => $path,
            'order' => $maxOrder + 1,
        ]);

        return back();
    }

    public function destroy(Item $item, ItemImage $image)
    {
        abort_if($image->item_id !== $item->id, 403);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back();
    }
}
