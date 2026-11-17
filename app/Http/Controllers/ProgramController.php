<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::published()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('programs.index', compact('programs'));
    }

    public function show(Program $program)
    {
        abort_unless($program->is_published, 404);

        $program->load('images');

        $otherPrograms = Program::published()
            ->whereKeyNot($program->id)
            ->latest()
            ->take(3)
            ->get();

        return view('programs.show', compact('program', 'otherPrograms'));
    }
}
