<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Equipe;
use App\Models\Resultat;

class ResultatController extends Controller
{
    public function index($rai_id, $cou_id)
    {
        $course = Course::where('RAI_ID', $rai_id)->where('COU_ID', $cou_id)->firstOrFail();
        
        // On ne charge QUE le chef. Les membres seront chargés par la vue via l'attribut.
        $equipes = Equipe::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->with('chef') 
                        ->get();

        // Tri par temps (les null à la fin)
        $equipes = $equipes->sortBy(function($equipe) {
             return $equipe->resultat_cache->RES_TEMPS ?? '99:99:99';
        });

        $userId = Auth::id();
        $canManage = DB::table('vik_responsable_raid')->where('UTI_ID', $userId)->exists() 
                  || DB::table('vik_responsable_course')->where('UTI_ID', $userId)->exists();

        return view('resultats.index', compact('course', 'equipes', 'canManage'));
    }

    public function store(Request $request, $rai_id, $cou_id)
    {
        $userId = Auth::id();
        $canManage = DB::table('vik_responsable_raid')->where('UTI_ID', $userId)->exists() 
                  || DB::table('vik_responsable_course')->where('UTI_ID', $userId)->exists();

        if (!$canManage) {
            abort(403);
        }

        foreach ($request->resultats as $equId => $data) {
            if (empty($data['rang']) && empty($data['temps'])) continue;

            Resultat::updateOrCreate(
                [
                    'RAI_ID' => $rai_id,
                    'COU_ID' => $cou_id,
                    'EQU_ID' => $equId
                ],
                [
                    'RES_RANG'  => $data['rang'],
                    'RES_TEMPS' => $data['temps'],
                    'RES_POINT' => $data['points'] ?? 0
                ]
            );
        }

        return back()->with('success', 'Classement mis à jour !');
    }
}