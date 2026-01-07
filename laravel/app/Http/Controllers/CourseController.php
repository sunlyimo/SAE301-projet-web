<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\TypeCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Affiche la liste des courses avec leurs relations.
     */
    public function index()
    {
        $courses = Course::with(['type', 'responsable'])->get();
        return view('courses.index', compact('courses'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $types = TypeCourse::all();
        return view('courses.create', compact('types'));
    }

    /**
     * Enregistrement d'une nouvelle course.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $rai_id = 1; 
        $lastCouId = Course::where('RAI_ID', $rai_id)->max('COU_ID') ?? 0;
        $newCouId = $lastCouId + 1;

        Course::create(array_merge($validated, [
            'RAI_ID' => $rai_id,
            'COU_ID' => $newCouId,
            'UTI_ID' => Auth::id(), 
        ]));

        return redirect()->route('courses.index')->with('success', 'Course créée avec succès.');
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

        return view('courses.edit', compact('course', 'types'));
    }

    /**
     * Mise à jour de la course.
     */
    public function update(Request $request, $rai_id, $cou_id)
    {
        $course = Course::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->firstOrFail();

        $validated = $this->validateRequest($request);

        $course->update($validated);

        return redirect()->route('courses.index')->with('success', 'Course mise à jour avec succès.');
    }

    /**
     * Suppression d'une course.
     */
    public function destroy($rai_id, $cou_id)
    {
        $course = Course::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->firstOrFail();
        
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course supprimée.');
    }

    public function coursesByRaid($raid_id)
    {
        $raid = \App\Models\Raid::findOrFail($raid_id);
        $courses = Course::where('RAI_ID', $raid_id)
                        ->with(['type', 'responsable'])
                        ->get();
        return view('courses.index', compact('courses', 'raid'));
    }

    /**
     * Méthode de validation centralisée.
     *
     */
    protected function validateRequest(Request $request)
    {
        return $request->validate([
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
        ]);
    }
}