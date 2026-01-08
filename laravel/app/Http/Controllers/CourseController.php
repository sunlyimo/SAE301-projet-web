<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\TypeCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Affiche la liste des courses avec leurs relations.
     */
    public function index()
    {
        $courses = Course::with(['type', 'responsable', 'raid', 'tranche'])->get();
        return view('courses.index', compact('courses'));
    }

    /**
     * Affiche les courses dont l'utilisateur est responsable.
     */
    public function myCourses()
    {
        $courses = Course::where('UTI_ID', auth()->id())
                ->with(['type', 'raid', 'tranche'])
                ->get();

        return view('courses.my-courses', compact('courses'));
    }

    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        $types = TypeCourse::all();
        $users = User::orderBy('UTI_NOM_UTILISATEUR')->get();
        $raids = \App\Models\Raid::where('UTI_ID', auth()->id())->get();

        $selectedRaidId = $request->query('raid_id');

        return view('courses.create', compact('types','users', 'raids', 'selectedRaidId'));
    }

    /**
     * Enregistrement d'une nouvelle course.
     */
    public function store(Request $request) {
        $validated = $this->validateRequest($request);
        $rai_id = $request->rai_id;
        $raid = \App\Models\Raid::findOrFail($rai_id);
        $responsableId = $request->responsable_id;
        $userInfo = DB::table('vik_utilisateur')->where('UTI_ID', $responsableId)->first();

        if (!$userInfo) {
            return redirect()->back()->withErrors(['responsable_id' => "L'utilisateur n'existe pas."]);
        }

        // Validation des dates par rapport au raid
        $courseStart = \Carbon\Carbon::parse($validated['COU_DATE_DEBUT']);
        $courseEnd = \Carbon\Carbon::parse($validated['COU_DATE_FIN']);
        $raidStart = \Carbon\Carbon::parse($raid->RAI_RAID_DATE_DEBUT);
        $raidEnd = \Carbon\Carbon::parse($raid->RAI_RAID_DATE_FIN);

        if ($courseStart < $raidStart || $courseEnd > $raidEnd) {
            return redirect()->back()->withErrors(['COU_DATE_DEBUT' => 'Les dates de la course doivent être comprises dans les dates du raid.']);
        }

        $userData = (array) $userInfo;
        unset($userData['created_at']);
        unset($userData['updated_at']);

        DB::table('vik_responsable_course')->updateOrInsert(
            ['UTI_ID' => $responsableId],
            $userData
        );
        $lastCouId = Course::where('RAI_ID', $rai_id)->max('COU_ID') ?? 0;
        $newCouId = $lastCouId + 1;
        Course::create(array_merge($validated, [
            'RAI_ID' => $rai_id,
            'COU_ID' => $newCouId,
            'UTI_ID' => $responsableId,
        ]));
        return redirect()->route('raids.courses', $rai_id)->with('success', 'La course a été créée avec succès !');
    }

    /**
     * Formulaire d'édition.
     */
    public function edit($rai_id, $cou_id)
    {
        $course = Course::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->firstOrFail();

        $types = TypeCourse::all();
        $users = User::orderBy('UTI_NOM_UTILISATEUR')->get();

        // Vérifier les permissions de l'utilisateur
        $isAdmin = DB::table('vik_administrateur')->where('UTI_ID', auth()->id())->exists();
        $isRaidResponsable = DB::table('vik_raid')->where('RAI_ID', $rai_id)->where('UTI_ID', auth()->id())->exists();

        if ($isAdmin) {
            $raids = \App\Models\Raid::all();
        } elseif ($isRaidResponsable) {
            $raids = \App\Models\Raid::where('UTI_ID', auth()->id())->get();
        } else {
            // Pour les responsables de course spécifiques, ne montrer que le raid de cette course
            $raids = \App\Models\Raid::where('RAI_ID', $rai_id)->get();
        }

        return view('courses.edit', compact('course', 'types', 'raids', 'users'));
    }

    /**
     * Mise à jour de la course.
     */
    public function update(Request $request, $rai_id, $cou_id)
    {
        \Log::info('Course update called', [
            'user_id' => auth()->id(),
            'rai_id' => $rai_id,
            'cou_id' => $cou_id,
            'request_data' => $request->all()
        ]);

        $course = Course::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->firstOrFail();

        \Log::info('Course found', ['course' => $course->toArray()]);

        $validated = $this->validateRequest($request);
        \Log::info('Validation passed', ['validated' => $validated]);

        // Préparer les données pour la mise à jour
        $updateData = $validated;
        
        // Mapper responsable_id vers UTI_ID
        if (isset($validated['responsable_id'])) {
            $updateData['UTI_ID'] = $validated['responsable_id'];
            unset($updateData['responsable_id']);
        }
        
        // Ne pas mettre à jour RAI_ID et COU_ID (clé primaire)
        unset($updateData['rai_id']);

        \Log::info('Course update data:', $updateData);
        $course->update($updateData);
        \Log::info('Course updated successfully', ['course_id' => $course->COU_ID, 'raid_id' => $course->RAI_ID]);

        // Si l'utilisateur est responsable de cette course (même après modification), rediriger vers "Mes Courses"
        if ($course->UTI_ID == auth()->id()) {
            \Log::info('Redirecting to courses.my-courses (user is course responsable)');
            return redirect()->route('courses.my-courses')->with('success', 'Course mise à jour avec succès.');
        }

        // Sinon, rediriger vers les courses du raid
        \Log::info('Redirecting to raids.courses (user is raid responsable)', ['rai_id' => $rai_id]);
        return redirect()->route('raids.courses', $rai_id)->with('success', 'Course mise à jour avec succès.');
    }

    /**
     * Suppression d'une course.
     */
    public function destroy($rai_id, $cou_id)
    {
        $course = Course::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->firstOrFail();

        // Vérifier s'il y a des équipes associées à cette course
        $hasTeams = \App\Models\Equipe::where('RAI_ID', $rai_id)
                                      ->where('COU_ID', $cou_id)
                                      ->exists();

        if ($hasTeams) {
            return redirect()->back()->withErrors([
                'delete' => 'Impossible de supprimer cette course car des équipes y sont inscrites. Veuillez d\'abord supprimer ou déplacer les équipes.'
            ]);
        }

        $course->delete();

        return redirect()->route('courses.my-courses')->with('success', 'Course supprimée avec succès.');
    }    public function coursesByRaid($raid_id)
    {
        $raid = \App\Models\Raid::findOrFail($raid_id);
        $courses = Course::where('RAI_ID', $raid_id)
                ->with(['type', 'responsable', 'tranche'])
                ->get();

        // Ajouter manuellement le comptage des équipes et des participants pour chaque course
        foreach ($courses as $course) {
            $course->equipes_count = \App\Models\Equipe::where('RAI_ID', $course->RAI_ID)
                                                        ->where('COU_ID', $course->COU_ID)
                                                        ->count();

            $course->participants_count = \DB::table('vik_appartient')
                                            ->where('RAI_ID', $course->RAI_ID)
                                            ->where('COU_ID', $course->COU_ID)
                                            ->count();
        }

        return view('courses.index', compact('courses', 'raid'));
    }

    /**
     * Affiche les équipes d'une course pour le responsable de course.
     */
    public function manageTeams($rai_id, $cou_id)
    {
        $course = Course::where('RAI_ID', $rai_id)
                       ->where('COU_ID', $cou_id)
                       ->with('raid')
                       ->firstOrFail();

        // Vérifier que l'utilisateur est responsable de cette course
        if ($course->UTI_ID != auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à gérer les équipes de cette course.');
        }

        $teams = \App\Models\Equipe::where('RAI_ID', $rai_id)
                                  ->where('COU_ID', $cou_id)
                                  ->with(['chef', 'membres'])
                                  ->get();

        // Récupérer les équipes validées depuis la session
        $validatedTeamsKey = "validated_teams_{$rai_id}_{$cou_id}";
        $validatedTeamIds = session($validatedTeamsKey, []);

        // Séparer les équipes validées et non validées
        $validatedTeams = $teams->filter(function($team) use ($validatedTeamIds) {
            return in_array($team->EQU_ID, $validatedTeamIds);
        });
        $pendingTeams = $teams->filter(function($team) use ($validatedTeamIds) {
            return !in_array($team->EQU_ID, $validatedTeamIds);
        });

        return view('courses.manage-teams', compact('course', 'validatedTeams', 'pendingTeams'));
    }

    /**
     * Méthode de validation centralisée.
     *
     */
    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'rai_id' => 'sometimes|exists:vik_raid,RAI_ID', // Optionnel pour l'update
            'COU_NOM' => 'required|string|max:50',
            'TYP_ID' => 'required|exists:vik_course_type,TYP_ID',
            'COU_LIEU' => 'required|string|max:100',
            
            'COU_DATE_DEBUT' => 'required|date',
            'COU_DATE_FIN' => 'required|date|after:COU_DATE_DEBUT',
            
            'COU_PRIX' => 'required|numeric|min:0',
            'COU_PRIX_ENFANT' => 'nullable|numeric|min:0',
            'COU_REPAS_PRIX' => 'nullable|numeric|min:0',
            'COU_REDUCTION' => 'nullable|numeric|min:0',
            'COU_AGE_MIN' => 'required|integer|min:0',
            'COU_AGE_SEUL' => 'required|integer|gte:COU_AGE_MIN',
            'COU_AGE_ACCOMPAGNATEUR' => 'nullable|integer|min:0',
            'DIF_NIVEAU' => 'required|integer|between:1,5',
            'COU_PARTICIPANT_MIN' => 'required|integer|min:1',
            'COU_PARTICIPANT_MAX' => 'required|integer|gte:COU_PARTICIPANT_MIN',
            'COU_EQUIPE_MIN' => 'required|integer|min:1',
            'COU_EQUIPE_MAX' => 'required|integer|gte:COU_EQUIPE_MIN',
            'COU_PARTICIPANT_PAR_EQUIPE_MAX' => 'required|integer|min:1',
            'responsable_id' => 'required|exists:vik_utilisateur,UTI_ID',
        ]);
    }
}