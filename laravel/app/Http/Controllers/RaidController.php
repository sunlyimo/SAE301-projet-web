<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raid;

class RaidController extends Controller
{
    public function showRaid() {
        return view('raid')->with(['raids',[Raid::class, 'getFuturRaid']]);
    }
}
