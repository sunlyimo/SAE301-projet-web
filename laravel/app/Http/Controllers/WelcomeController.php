<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raid;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index() {
        return view('welcome')
        ->with('raids',Raid::getFuturRaid());
    }
}
