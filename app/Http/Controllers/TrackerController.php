<?php

namespace App\Http\Controllers;

use App\Models\Stage;

class TrackerController extends Controller
{
    public function visual()
    {
        $stages = Stage::with(['sections.items.latestUpdate'])
            ->orderBy('order')
            ->get();

        return view('tracker.visual', compact('stages'));
    }

    public function detail()
    {
        $stages = Stage::with(['sections.items.latestUpdate'])
            ->orderBy('order')
            ->get();

        return view('tracker.detail', compact('stages'));
    }
}
