<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'image_layout'       => 'sometimes|in:B,C',
            'hide_images_mobile' => 'sometimes|boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, (string) $value);
        }

        return back();
    }
}
