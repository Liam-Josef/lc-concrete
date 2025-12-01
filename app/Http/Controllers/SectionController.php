<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Lesson;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function store(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $section = Section::create([
            'title' => $validated['title'],
        ]);

        // Attach the section to the lesson via pivot table
        $lesson->sections()->attach($section->id);

        return redirect()->back()->with('success', 'Section added and linked to lesson.');
    }

}
