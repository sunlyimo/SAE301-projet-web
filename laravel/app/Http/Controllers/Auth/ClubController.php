<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::all();
        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'CLU_NOM' => 'required|string|max:50',
            'CLU_RUE' => 'required|string|max:100',
            'CLU_CODE_POSTAL' => 'required|string|max:6',
            'CLU_VILLE' => 'required|string|max:50',
        ]);

        Club::create($request->all());

        return redirect()->route('clubs.index')->with('success', 'Le club a été ajouté avec succès.');
    }

    public function show(Club $club)
    {
        return view('clubs.show', compact('club'));
    }

    public function edit(Club $club)
    {
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        $request->validate([
            'CLU_NOM' => 'required|string|max:50',
            'CLU_RUE' => 'required|string|max:100',
            'CLU_CODE_POSTAL' => 'required|string|max:6',
            'CLU_VILLE' => 'required|string|max:50',
        ]);

        $club->update($request->all());

        return redirect()->route('clubs.index')->with('success', 'Le club a été modifié avec succès.');
    }

    public function destroy(Club $club)
    {
        $club->delete();

        return redirect()->route('clubs.index')->with('success', 'Le club a été supprimé avec succès.');
    }
}
