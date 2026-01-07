<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raid;
use Illuminate\Support\Facades\DB;

class RaidController extends Controller
{
    public function index() {
        return view('raid')
        ->with('raids',Raid::getFuturRaid());
    }

    public function create() /* affichage du formulaire */
    {
        $clubs = DB::table('VIK_CLUB')->orderBy('CLU_NOM')->pluck('CLU_NOM', 'CLU_ID');
        $responsables = DB::table('VIK_RESPONSABLE_RAID')
            ->selectRaw("UTI_ID, CONCAT(UTI_PRENOM, ' ', UTI_NOM) as name")
            ->orderBy('UTI_NOM')
            ->pluck('name', 'UTI_ID');
        return view('raid-create', compact('clubs', 'responsables'));
    }

    public function store(Request $request) /* ajout d'un nouveau raid à la base de données */
    {
        $validated = $request->validate([
            'RAI_NOM' => 'required|max:50',
            'RAI_LIEU' => 'required|max:100',
            'RAI_WEB' => 'nullable|url',
            'CLU_ID' => 'required|exists:VIK_CLUB,CLU_ID',
            'UTI_ID' => 'required|exists:VIK_RESPONSABLE_RAID,UTI_ID',
            'RAI_RAID_DATE_DEBUT' => 'required|date',
            'RAI_RAID_DATE_FIN' => 'required|date|after_or_equal:RAI_RAID_DATE_DEBUT',
            'RAI_INSCRI_DATE_DEBUT' => 'required|date',
            'RAI_INSCRI_DATE_FIN' => 'required|date|after_or_equal:RAI_INSCRI_DATE_DEBUT',
            'RAI_CONTACT' => 'required|email|max:100',
            'RAI_TELEPHONE' => 'nullable|regex:/^[0-9\s\.\-\+\(\)]{10,20}$/|max:20',
            'RAI_IMAGE' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Raid::create($validated);

        return redirect('/')->with('success', 'Raid créé avec succès !');
    }
}
