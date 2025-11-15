<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('courses.index');
})->middleware(['auth'])->name('dashboard');

// Public course routes
Route::resource('courses', CourseController::class)->only(['index', 'create', 'show']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Course management
    Route::resource('courses', CourseController::class)->except(['index', 'show']);

    
    // Episodes
    Route::prefix('courses/{course}')->group(function () {
        Route::get('episodes/create', [EpisodeController::class, 'create'])->name('episodes.create');
        Route::post('episodes', [EpisodeController::class, 'store'])->name('courses.episodes.store');
        Route::get('episodes/{episode}', [EpisodeController::class, 'show'])->name('courses.episodes.show');
        Route::get('episodes/{episode}/edit', [EpisodeController::class, 'edit'])->name('episodes.edit');
        Route::put('episodes/{episode}', [EpisodeController::class, 'update'])->name('episodes.update');
        Route::delete('episodes/{episode}', [EpisodeController::class, 'destroy'])->name('episodes.destroy');
    });
    
    // Enrollments
    Route::post('courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('enrollments.enroll');
    Route::post('courses/{course}/unenroll', [EnrollmentController::class, 'unenroll'])->name('enrollments.unenroll');
    Route::get('my-courses', [EnrollmentController::class, 'myCourses'])->name('enrollments.my-courses');
});

require __DIR__.'/auth.php';