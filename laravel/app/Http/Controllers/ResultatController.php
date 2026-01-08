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

        // Lire le fichier et parser avec des virgules comme délimiteur
        $csv = array_map(function($line) {
            return str_getcsv($line, ',');
        }, file($path));

        // Vérifier que le CSV n'est pas vide
        if (count($csv) < 2) {
            return back()->with('error', 'Le fichier CSV est vide ou invalide.');
        }

        // Récupérer les en-têtes (première ligne)
        $headers = array_map('trim', $csv[0]);

        // Vérifier que le fichier utilise bien des virgules comme séparateur
        // Si la première ligne n'a qu'une seule colonne, c'est probablement un autre délimiteur
        if (count($headers) === 1 && strpos($headers[0], ':') !== false) {
            return back()->with('error', 'Format CSV invalide. Le fichier doit utiliser des virgules (,) comme séparateur, pas des deux-points (:).');
        }

        if (count($headers) === 1) {
            return back()->with('error', 'Format CSV invalide. Le fichier doit utiliser des virgules (,) comme séparateur entre les colonnes.');
        }

        // Vérifier que les colonnes nécessaires existent
        $requiredColumns = ['equ_id', 'rang', 'temps', 'points'];
        $missingColumns = array_diff($requiredColumns, array_map('strtolower', $headers));

        if (!empty($missingColumns)) {
            return back()->with('error', 'Colonnes manquantes dans le CSV: ' . implode(', ', $missingColumns));
        }

        // Créer un mapping des colonnes
        $columnIndexes = [];
        foreach ($headers as $index => $header) {
            $columnIndexes[strtolower(trim($header))] = $index;
        }

        $imported = 0;
        $errors = [];

        // Traiter chaque ligne (sauf la première qui contient les en-têtes)
        for ($i = 1; $i < count($csv); $i++) {
            $row = $csv[$i];

            // Ignorer les lignes vides
            if (empty(array_filter($row))) {
                continue;
            }

            try {
                $equId = trim($row[$columnIndexes['equ_id']] ?? '');
                $rang = trim($row[$columnIndexes['rang']] ?? '');
                $temps = trim($row[$columnIndexes['temps']] ?? '');
                $points = intval(trim($row[$columnIndexes['points']] ?? 0));

                // Vérifier que l'équipe existe
                $equipe = Equipe::where('RAI_ID', $rai_id)
                                ->where('COU_ID', $cou_id)
                                ->where('EQU_ID', $equId)
                                ->first();

                if (!$equipe) {
                    $errors[] = "Ligne " . ($i + 1) . ": Équipe ID $equId introuvable.";
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
                        'EQU_ID' => $equId
                    ],
                    [
                        'RES_RANG'  => $rang,
                        'RES_TEMPS' => $temps,
                        'RES_POINT' => $points
                    ]
                );

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Ligne " . ($i + 1) . ": " . $e->getMessage();
            }
        }

        $message = "$imported résultat(s) importé(s) avec succès.";
        if (!empty($errors)) {
            $message .= " Erreurs: " . implode(' | ', $errors);
        }

        return back()->with('success', $message);
    }
}