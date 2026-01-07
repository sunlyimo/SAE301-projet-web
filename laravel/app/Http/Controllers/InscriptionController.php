<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Equipe;
use App\Models\Appartient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InscriptionController extends Controller
{
    public function show($rai_id, $cou_id)
    {
        $course = Course::where('RAI_ID', $rai_id)->where('COU_ID', $cou_id)->firstOrFail();
        
        $equipes = Equipe::where('RAI_ID', $rai_id)
                         ->where('COU_ID', $cou_id)
                         ->with(['chef', 'membres'])
                         ->get();

        return view('courses.inscription', compact('course', 'equipes'));
    }

    public function createTeam(Request $request, $rai_id, $cou_id)
    {
        $request->validate([
            'EQU_NOM' => 'required|string|max:50',
            'EQU_IMAGE' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('EQU_IMAGE')) {
            $imagePath = $request->file('EQU_IMAGE')->store('equipes', 'public');
        }

        $lastEquipe = \App\Models\Equipe::where('RAI_ID', $rai_id)
                            ->where('COU_ID', $cou_id)
                            ->max('EQU_ID') ?? 0;
        $newEquId = $lastEquipe + 1;

        \App\Models\Equipe::create([
            'RAI_ID' => $rai_id,
            'COU_ID' => $cou_id,
            'EQU_ID' => $newEquId,
            'UTI_ID' => \Illuminate\Support\Facades\Auth::id(),
            'EQU_NOM' => $request->EQU_NOM,
            'EQU_IMAGE' => $imagePath
        ]);

        return redirect()->route('teams.show', [$rai_id, $cou_id, $newEquId])
                        ->with('success', "L'équipe \"{$request->EQU_NOM}\" a été créée !");
    }

    public function joinTeam(Request $request, $rai_id, $cou_id)
    {
        $request->validate([
            'EQU_ID' => 'required|integer'
        ]);
        Appartient::create([
            'UTI_ID' => Auth::id(),
            'RAI_ID' => $rai_id,
            'COU_ID' => $cou_id,
            'EQU_ID' => $request->EQU_ID
        ]);

        return redirect()->route('courses.index')->with('success', "Vous avez rejoint l'équipe avec succès !");
    }
}