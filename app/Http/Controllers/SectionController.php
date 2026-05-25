<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $maxOrder = $stage->sections()->max('order') ?? 0;

        $stage->sections()->create([
            'name'  => $validated['name'],
            'order' => $maxOrder + 1,
        ]);

        return back();
    }
}
