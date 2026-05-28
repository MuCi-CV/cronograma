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
            'auto_refresh'       => 'sometimes|boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : (string) $value);
        }

        return back();
    }
}
