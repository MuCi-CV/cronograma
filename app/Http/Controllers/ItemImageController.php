<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class ItemImageController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $file    = $request->file('image');
        $manager = ImageManager::usingDriver(GdDriver::class);
        $uuid    = (string) Str::uuid();
        $dir     = 'items/' . $item->id;

        // Display version: max 1200px en cualquier lado, JPEG 82%
        $display = $manager->decodePath($file->getPathname());
        $display->scaleDown(1200, 1200);
        $displayPath = $dir . '/' . $uuid . '.jpg';
        Storage::disk('public')->put(
            $displayPath,
            (string) $display->encodeUsingFormat(Format::JPEG, quality: 82)
        );

        // Original version: sin redimensionar, JPEG 90%
        $original     = $manager->decodePath($file->getPathname());
        $originalPath = $dir . '/' . $uuid . '_orig.jpg';
        Storage::disk('public')->put(
            $originalPath,
            (string) $original->encodeUsingFormat(Format::JPEG, quality: 90)
        );

        $maxOrder = $item->images()->max('order') ?? 0;

        $item->images()->create([
            'path'          => $displayPath,
            'original_path' => $originalPath,
            'order'         => $maxOrder + 1,
        ]);

        return back();
    }

    public function destroy(Item $item, ItemImage $image)
    {
        abort_if($image->item_id !== $item->id, 403);

        Storage::disk('public')->delete($image->path);

        if ($image->original_path) {
            Storage::disk('public')->delete($image->original_path);
        }

        $image->delete();

        return back();
    }
}
