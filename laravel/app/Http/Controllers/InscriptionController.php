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
    /**
     * Vérifie si l'utilisateur est déjà inscrit à une course qui chevauche les horaires
     *
     * @param int $userId
     * @param int $raiId
     * @param int $couId
     * @return array|null Retourne les détails de la course en conflit ou null
     */
    private function checkCourseOverlap($userId, $raiId, $couId)
    {
        // Récupérer la course cible
        $targetCourse = Course::where('RAI_ID', $raiId)
                              ->where('COU_ID', $couId)
                              ->firstOrFail();

        // Récupérer toutes les courses auxquelles l'utilisateur est déjà inscrit
        $userCourses = DB::table('vik_appartient as a')
            ->join('vik_course as c', function($join) {
                $join->on('a.RAI_ID', '=', 'c.RAI_ID')
                     ->on('a.COU_ID', '=', 'c.COU_ID');
            })
            ->where('a.UTI_ID', $userId)
            ->where(function($query) use ($raiId, $couId) {
                // Exclure la course actuelle
                $query->where('a.RAI_ID', '!=', $raiId)
                      ->orWhere('a.COU_ID', '!=', $couId);
            })
            ->select('c.*')
            ->get();

        // Vérifier également si l'utilisateur est chef d'une équipe
        $userAsChef = DB::table('vik_equipe as e')
            ->join('vik_course as c', function($join) {
                $join->on('e.RAI_ID', '=', 'c.RAI_ID')
                     ->on('e.COU_ID', '=', 'c.COU_ID');
            })
            ->where('e.UTI_ID', $userId)
            ->where(function($query) use ($raiId, $couId) {
                $query->where('e.RAI_ID', '!=', $raiId)
                      ->orWhere('e.COU_ID', '!=', $couId);
            })
            ->select('c.*')
            ->get();

        // Fusionner les deux collections
        $allUserCourses = $userCourses->merge($userAsChef)->unique('COU_ID');

        // Vérifier les chevauchements
        foreach ($allUserCourses as $userCourse) {
            $targetStart = \Carbon\Carbon::parse($targetCourse->COU_DATE_DEBUT);
            $targetEnd = \Carbon\Carbon::parse($targetCourse->COU_DATE_FIN);
            $userStart = \Carbon\Carbon::parse($userCourse->COU_DATE_DEBUT);
            $userEnd = \Carbon\Carbon::parse($userCourse->COU_DATE_FIN);

            // Vérifier si les horaires se chevauchent
            // Deux périodes se chevauchent si : (start1 < end2) AND (start2 < end1)
            if ($targetStart->lt($userEnd) && $userStart->lt($targetEnd)) {
                return [
                    'course_name' => $userCourse->COU_NOM,
                    'start' => $userStart->format('d/m/Y H:i'),
                    'end' => $userEnd->format('d/m/Y H:i')
                ];
            }
        }

        return null;
    }

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

        // Vérifier les chevauchements d'horaires pour le chef d'équipe
        $userId = Auth::id();
        $overlap = $this->checkCourseOverlap($userId, $rai_id, $cou_id);

        if ($overlap) {
            return redirect()->back()->withErrors([
                'overlap' => "Vous ne pouvez pas créer une équipe pour cette course car vous êtes déjà inscrit à la course \"{$overlap['course_name']}\" dont les horaires se chevauchent ({$overlap['start']} - {$overlap['end']})."
            ])->withInput();
        }

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

        // Vérifier les chevauchements d'horaires
        $userId = Auth::id();
        $overlap = $this->checkCourseOverlap($userId, $rai_id, $cou_id);

        if ($overlap) {
            return redirect()->back()->withErrors([
                'overlap' => "Vous ne pouvez pas rejoindre cette équipe car vous êtes déjà inscrit à la course \"{$overlap['course_name']}\" dont les horaires se chevauchent ({$overlap['start']} - {$overlap['end']})."
            ])->withInput();
        }

        Appartient::create([
            'UTI_ID' => Auth::id(),
            'RAI_ID' => $rai_id,
            'COU_ID' => $cou_id,
            'EQU_ID' => $request->EQU_ID
        ]);

        return redirect()->route('courses.index')->with('success', "Vous avez rejoint l'équipe avec succès !");
    }
}