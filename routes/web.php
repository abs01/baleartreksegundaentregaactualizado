<?php

use App\Http\Controllers\CommentCRUDController;
use App\Http\Controllers\MeetingControllerCRUD;
use App\Http\Controllers\MunicipisControllerCRUD;
use App\Http\Controllers\PlacesOfInterestCRUDController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrekCRUDController;
use App\Http\Controllers\UserCRUDController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    // Rutes protegides per rol admin
    Route::middleware('CHECK-ROLEADMIN')->group(function () {

    Route::resource('userCRUD', UserCRUDController::class);
    // Trek CRUD routes
    Route::resource('trekCRUD', TrekCRUDController::class);

    Route::resource('municipalities', MunicipisControllerCRUD::class);
    // Places CRUD routes
    Route::resource('places', PlacesOfInterestCRUDController::class);

    Route::resource('meetings', MeetingControllerCRUD::class);

    Route::resource('comments', CommentCRUDController::class);

        Route::delete('/commentCRUD/destroy/{image}', [CommentCRUDController::class, 'destroyImage'])->name('image.destroy');
});});

require __DIR__.'/auth.php';
