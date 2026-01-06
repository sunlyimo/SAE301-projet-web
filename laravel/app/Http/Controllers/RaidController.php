<?php

namespace App\Http\Controllers;

use App\Models\Raid;
use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RaidController extends Controller
{
    public function create()
    {
        return view('raids.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'CLU_ID' => 'required|exists:VIK_CLUB,CLU_ID',
            'UTI_ID' => 'required|exists:users,id',
            'RAI_NOM' => 'required|string|max:255',
            'RAI_RAID_DATE_DEBUT' => 'required|date',
            'RAI_RAID_DATE_FIN' => 'required|date|after:RAI_RAID_DATE_DEBUT',
            'RAI_INSCRI_DATE_DEBUT' => 'required|date',
            'RAI_INSCRI_DATE_FIN' => 'required|date|after:RAI_INSCRI_DATE_DEBUT|before:RAI_RAID_DATE_DEBUT',
            'RAI_CONTACT' => 'required|email|max:255',
            'RAI_WEB' => 'required|url|max:255',
            'RAI_LIEU' => 'required|string|max:255',
            'RAI_IMAGE' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('RAI_IMAGE')) {
            $image = $request->file('RAI_IMAGE');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/raids', $imageName);
            $validated['RAI_IMAGE'] = $imageName;
        }

        Raid::create($validated);

        return redirect()->route('raids.create')->with('success', 'Raid créé avec succès!');
    }

    public function searchClubs(Request $request)
    {
        $search = $request->get('search', '');

        $clubs = Club::where('CLU_NOM', 'LIKE', "%{$search}%")
            ->orWhere('CLU_VILLE', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['CLU_ID', 'CLU_NOM', 'CLU_VILLE']);

        return response()->json($clubs);
    }

    public function searchUsers(Request $request)
    {
        $search = $request->get('search', '');

        $users = User::where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}
