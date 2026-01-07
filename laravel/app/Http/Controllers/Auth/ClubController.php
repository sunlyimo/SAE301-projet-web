<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::all();
        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent créer des clubs.');
        }

        return view('clubs.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent créer des clubs.');
        }

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
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent modifier des clubs.');
        }

        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent modifier des clubs.');
        }

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
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent supprimer des clubs.');
        }

        $club->delete();

        return redirect()->route('clubs.index')->with('success', 'Le club a été supprimé avec succès.');
    }
}
