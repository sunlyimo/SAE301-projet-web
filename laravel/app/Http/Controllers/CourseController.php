<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('races.index', compact('courses'));
    }

    public function indexRaceOfRaid($id)
    {
        $courses = Course::getRaceOfRaid($id);
        return view('races.index', compact('courses'));
    }
}
