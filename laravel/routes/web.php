<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RaidController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/repair', function() {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return "le cache a été vidé";
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/raids/create', [RaidController::class, 'create'])->name('raids.create');
    Route::post('/raids', [RaidController::class, 'store'])->name('raids.store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('user.update');
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

  Route::post('/logs/{disk}/{file}/delete', function(string $disk, string $file) {
    Storage::disk($disk)->delete($file);
    return Redirect::back();
  }) -> name("logs.delete");
});

Route::get('/races', [CourseController::class, 'index'])->name('races.index');

Route::get('/about', function () {
    return view('about.about');
})->name('about');

