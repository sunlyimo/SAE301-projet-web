<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\RaidController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ClubController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ResultatController;

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/repair', function() {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return "le cache a été vidé";
});

Route::resource('clubs', ClubController::class);

Route::get('/raids', [RaidController::class, 'index'])->name('raids.index');
Route::get('/raids/{raid_id}/courses', [CourseController::class, 'coursesByRaid'])->name('raids.courses');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/profile', [UserController::class, 'show'])->name('user.profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('user.update');

    Route::get('/raids/create', [RaidController::class, 'create'])->name('raids.create');
    Route::post('/raids', [RaidController::class, 'store'])->name('raids.store');

    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    
    Route::get('/courses/{rai_id}/{cou_id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::patch('/courses/{rai_id}/{cou_id}', [CourseController::class, 'update'])->name('courses.update');

    Route::get('/courses/{rai_id}/{cou_id}/inscription', [InscriptionController::class, 'show'])->name('courses.inscription');
    
    Route::post('/courses/{rai_id}/{cou_id}/team/create', [InscriptionController::class, 'createTeam'])->name('courses.team.create');
    Route::post('/courses/{rai_id}/{cou_id}/team/join', [InscriptionController::class, 'joinTeam'])->name('courses.team.join');

    Route::get('/teams/{rai_id}/{cou_id}/{equ_id}', [TeamController::class, 'show'])->name('teams.show');
    Route::post('/teams/{rai_id}/{cou_id}/{equ_id}/add', [TeamController::class, 'addMember'])->name('teams.add');

    Route::get('/courses/{rai_id}/{cou_id}/resultats', [ResultatController::class, 'index'])->name('resultats.index');
    Route::post('/courses/{rai_id}/{cou_id}/resultats', [ResultatController::class, 'store'])->name('resultats.store');

    Route::get('/logs/{file}', function (string $file) {
      if ($file === 'laravel') {
        $content = Storage::disk('laravelLog')->get('laravel.log');
        return view('log', [
            'file'=>'laravel.log', 
            'content'=>$content, 
            'route'=>route('logs.delete', ['disk'=>'laravelLog', 'file'=>'laravel.log'])
            ]);
      } else {
        Log::debug("accessing log path : ".Storage::disk('log')->path("$file.log"));
        if (Storage::disk('log')->exists("$file.log")) {
          Log::debug("exists : OK");
          $content = Storage::disk('log')->get("$file.log");
          return view('log', [
            'file'=>"$file.log", 
            'content'=>$content, 
            'route'=>null
            ]);
        } else {
          Log::debug("exists : OK");
          return "<h1>$file.log</h1><p style='color:red'>Not Found</p>";
        }
      }
    });
});

Route::post('/logs/{disk}/{file}/delete', function(string $disk, string $file) {
  Storage::disk($disk)->delete($file);
  return Redirect::back();
}) -> name("logs.delete");

Route::get('/about', function () {
    return view('about.about');
})->name('about');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
