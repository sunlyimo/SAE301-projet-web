<?php

namespace App\Http\Controllers;

use App\Models\Raid;
use App\Models\Club; // Supposons que tu as créé ce modèle
use App\Models\ResponsableRaid; 
use Illuminate\Http\Request;

class RaidController extends Controller
{
    public function create() /* affichage du formulaire */
    {
        //return view('raids.create', compact('clubs', 'responsables'));
        return view('raid-create');
    }

    public function store(Request $request) /* ajout d'un nouveau raid à la base de données */
    {
        $validated = $request->validate([
            'RAI_NOM' => 'required|max:50',
            'CLU_ID' => 'required|exists:VIK_CLUB,CLU_ID',
            'UTI_ID' => 'required|exists:VIK_RESPONSABLE_RAID,UTI_ID',
            'RAI_RAID_DATE_DEBUT' => 'required|date',
            'RAI_RAID_DATE_FIN' => 'required|date|after_or_equal:RAI_RAID_DATE_DEBUT',
            'RAI_INSCRI_DATE_DEBUT' => 'required|date',
            'RAI_INSCRI_DATE_FIN' => 'required|date|after_or_equal:RAI_INSCRI_DATE_DEBUT',
            'RAI_CONTACT' => 'required|email|max:100',
        ]);

        Raid::create($validated);

        return redirect()->route('raids.index')->with('success', 'Raid créé avec succès !');
    }
}