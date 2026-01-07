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

Route::resource('clubs', ClubController::class);

// Route pour afficher la page de création réussie
Route::get('/clubs/created/{club}/{token}', [ClubController::class, 'showCreated'])->name('clubs.created');

// Routes pour l'inscription des responsables
Route::get('/responsable/mailbox/{club_id}/{token}', [ClubController::class, 'showFakeMailbox'])->name('responsable.mailbox');
Route::post('/responsable/quick-validate/{club_id}/{token}', [ClubController::class, 'quickValidateResponsable'])->name('responsable.quick-validate');
Route::post('/responsable/refuse/{club_id}/{token}', [ClubController::class, 'refuseResponsable'])->name('responsable.refuse');
Route::get('/responsable/quick-validated/{club}/{token}', [ClubController::class, 'showQuickValidated'])->name('responsable.quick-validated');
Route::get('/responsable/register/{club_id}/{token}', [ClubController::class, 'showResponsableRegistration'])->name('responsable.register');
Route::post('/responsable/complete-registration', [ClubController::class, 'completeResponsableRegistration'])->name('responsable.complete-registration');

// Invitation routes for existing users
Route::get('/responsable/invitation/{club_id}/{user_id}/{token}', [ClubController::class, 'showInvitation'])->name('responsable.invitation.show');
// Allow GET to the accept URL to show the invitation page (some clients may request the link as GET)
Route::get('/responsable/invitation/{club_id}/{user_id}/{token}/accept', [ClubController::class, 'showInvitation'])->name('responsable.invitation.accept.show');

// Redirect link used in emails: immediately send user to login and set intended to the POST accept route
Route::get('/responsable/invitation/{club_id}/{user_id}/{token}/accept-login', [ClubController::class, 'redirectToLoginForInvitation'])->name('responsable.invitation.accept.login');

// Accept automatically after login (the intended URL will point here)
Route::get('/responsable/invitation/{club_id}/{user_id}/{token}/accept-after-login', [ClubController::class, 'acceptAfterLogin'])->name('responsable.invitation.accept.after_login');
Route::post('/responsable/invitation/{club_id}/{user_id}/{token}/accept', [ClubController::class, 'acceptInvitation'])->name('responsable.invitation.accept');
Route::post('/responsable/invitation/{club_id}/{user_id}/{token}/refuse', [ClubController::class, 'refuseInvitation'])->name('responsable.invitation.refuse');

// Admin helper: accept invitation on behalf of user (requires auth and admin)
Route::post('/responsable/invitation/{club_id}/{user_id}/{token}/admin-accept', [ClubController::class, 'adminAcceptInvitation'])->name('responsable.invitation.admin-accept')->middleware('auth');

// Route pour la notification de refus à l'admin
Route::get('/admin/refusal-notification/{club_id}/{token}', [ClubController::class, 'showAdminRefusalNotification'])->name('admin.refusal-notification');
Route::post('/admin/recreate-club/{club_id}/{token}', [ClubController::class, 'recreateClub'])->name('admin.recreate-club');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::get('/courses/{rai_id}/{cou_id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
Route::get('/raids/{raid_id}/courses', [App\Http\Controllers\CourseController::class, 'coursesByRaid'])->name('raids.courses');
Route::get('/raids', [RaidController::class, 'index'])->name('raids.index');

Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

Route::get('/courses/{rai_id}/{cou_id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
Route::patch('/courses/{rai_id}/{cou_id}', [CourseController::class, 'update'])->name('courses.update');

Route::get('/courses/{rai_id}/{cou_id}/inscription', [InscriptionController::class, 'show'])->name('courses.inscription');
Route::post('/courses/{rai_id}/{cou_id}/team/create', [InscriptionController::class, 'createTeam'])->name('courses.team.create');
Route::post('/courses/{rai_id}/{cou_id}/team/join', [InscriptionController::class, 'joinTeam'])->name('courses.team.join');

Route::get('/teams/{rai_id}/{cou_id}/{equ_id}', [TeamController::class, 'show'])->name('teams.show');
Route::post('/teams/{rai_id}/{cou_id}/{equ_id}/add', [TeamController::class, 'addMember'])->name('teams.add');
Route::delete('/teams/{rai_id}/{cou_id}/{equ_id}/remove/{uti_id}', [TeamController::class, 'removeMember'])->name('teams.remove');
Route::post('/teams/{rai_id}/{cou_id}/{equ_id}/toggle-chef', [TeamController::class, 'toggleChefParticipation'])->name('teams.toggle-chef');
Route::patch('/teams/{rai_id}/{cou_id}/{equ_id}/rpps/{uti_id}', [TeamController::class, 'updateRpps'])->name('teams.update-rpps');
Route::get('/api/users/search', [TeamController::class, 'searchUsers'])->name('users.search');



Route::get('/about', function () {
    return view('about.about');
})->name('about');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
