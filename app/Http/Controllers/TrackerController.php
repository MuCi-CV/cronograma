<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Stage;

class TrackerController extends Controller
{
    public function visual()
    {
        $stages = Stage::with(['sections.items.latestUpdate', 'sections.items.images'])
            ->orderBy('order')
            ->get();

        $imageLayout      = Setting::get('image_layout', 'B');
        $hideImagesMobile = Setting::get('hide_images_mobile', '0') === '1';

        return view('tracker.visual', compact('stages', 'imageLayout', 'hideImagesMobile'));
    }

    public function detail()
    {
        $stages = Stage::with(['sections.items.latestUpdate', 'sections.items.images'])
            ->orderBy('order')
            ->get();

        $imageLayout      = Setting::get('image_layout', 'B');
        $hideImagesMobile = Setting::get('hide_images_mobile', '0') === '1';

        return view('tracker.detail', compact('stages', 'imageLayout', 'hideImagesMobile'));
    }
}
