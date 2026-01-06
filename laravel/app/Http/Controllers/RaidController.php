<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RaidController extends Controller
{
    public function showRaid() {
        return view('raid');
    }
}
