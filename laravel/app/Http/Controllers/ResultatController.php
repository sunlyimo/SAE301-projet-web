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
        $equipes = Equipe::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->with('chef') 
                        ->get();
        // Tri par points décroissant (le plus de points en premier)
        $equipes = $equipes->sortByDesc(function($equipe) {
             return $equipe->resultat_cache->RES_POINT ?? 0;
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

            // Limiter les points à 9999 maximum
            $points = isset($data['points']) ? intval($data['points']) : 0;
            $points = min(max($points, 0), 9999);

            Resultat::updateOrCreate(
                [
                    'RAI_ID' => $rai_id,
                    'COU_ID' => $cou_id,
                    'EQU_ID' => $equId
                ],
                [
                    'RES_RANG'  => $data['rang'],
                    'RES_TEMPS' => $data['temps'],
                    'RES_POINT' => $points
                ]
            );
        }

        return back()->with('success', 'Classement mis à jour !');
    }

    public function importCsv(Request $request, $rai_id, $cou_id)
    {
        $userId = Auth::id();
        $canManage = DB::table('vik_responsable_raid')->where('UTI_ID', $userId)->exists()
                  || DB::table('vik_responsable_course')->where('UTI_ID', $userId)->exists();

        if (!$canManage) {
            abort(403);
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Lire le fichier et parser avec des point-virgules comme délimiteur
        $csv = array_map(function($line) {
            return str_getcsv($line, ';');
        }, file($path));

        // Vérifier que le CSV n'est pas vide
        if (count($csv) < 2) {
            return back()->with('error', 'Le fichier CSV est vide ou invalide.');
        }

        // Récupérer les en-têtes (première ligne)
        $headers = array_map('trim', $csv[0]);

        // Vérifier que le fichier a au moins une colonne
        if (count($headers) === 1) {
            return back()->with('error', 'Format CSV invalide. Le fichier doit utiliser des points-virgules (;) comme séparateur entre les colonnes.');
        }

        // Créer un mapping des colonnes
        $columnIndexes = [];
        foreach ($headers as $index => $header) {
            $columnIndexes[strtolower(trim($header))] = $index;
        }

        // Vérifier que les colonnes nécessaires existent
        $requiredColumns = ['equipe', 'temps', 'pts'];
        $missingColumns = array_diff($requiredColumns, array_keys($columnIndexes));

        if (!empty($missingColumns)) {
            return back()->with('error', 'Colonnes manquantes dans le CSV: ' . implode(', ', $missingColumns) . '. Colonnes disponibles: ' . implode(', ', array_keys($columnIndexes)));
        }

        $imported = 0;
        $errors = [];

        // Récupérer toutes les équipes de la course
        $equipes = Equipe::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->get()
                        ->keyBy('EQU_NOM');

        // Traiter chaque ligne (sauf la première qui contient les en-têtes)
        for ($i = 1; $i < count($csv); $i++) {
            $row = $csv[$i];

            // Ignorer les lignes vides
            if (empty(array_filter($row))) {
                continue;
            }

            try {
                $nomEquipe = trim($row[$columnIndexes['equipe']] ?? '');
                $temps = trim($row[$columnIndexes['temps']] ?? '');
                $points = intval(trim($row[$columnIndexes['pts']] ?? 0));

                // Trouver l'équipe par son nom
                $equipe = $equipes->get($nomEquipe);

                if (!$equipe) {
                    $errors[] = "Ligne " . ($i + 1) . ": Équipe '$nomEquipe' introuvable pour cette course.";
                    continue;
                }

                // Valider le format du temps (HH:MM:SS)
                if ($temps && !preg_match('/^\d{2}:\d{2}:\d{2}$/', $temps)) {
                    $errors[] = "Ligne " . ($i + 1) . ": Format de temps invalide '$temps'. Attendu: HH:MM:SS";
                    continue;
                }

                // Limiter les points à 9999 maximum
                $points = min(max($points, 0), 9999);

                Resultat::updateOrCreate(
                    [
                        'RAI_ID' => $rai_id,
                        'COU_ID' => $cou_id,
                        'EQU_ID' => $equipe->EQU_ID
                    ],
                    [
                        'RES_TEMPS' => $temps,
                        'RES_POINT' => $points,
                        'RES_RANG' => 0 // Sera calculé après
                    ]
                );

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Ligne " . ($i + 1) . ": " . $e->getMessage();
            }
        }

        // Calculer les rangs en fonction des points (ordre décroissant)
        if ($imported > 0) {
            $resultats = Resultat::where('RAI_ID', $rai_id)
                                ->where('COU_ID', $cou_id)
                                ->orderByDesc('RES_POINT')
                                ->get();

            $rang = 1;
            foreach ($resultats as $resultat) {
                $resultat->RES_RANG = $rang++;
                $resultat->save();
            }
        }

        $message = "$imported résultat(s) importé(s) avec succès.";
        if (!empty($errors)) {
            $message .= " Erreurs: " . implode(' | ', $errors);
        }

        return back()->with('success', $message);
    }
}