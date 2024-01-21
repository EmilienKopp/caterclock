<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DiaryEntryController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\ProjectController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix('time-logs')->middleware('auth')->group(function () {
    Route::get('/', [TimeLogController::class, 'index'])->name('timelog.index');
    Route::post('/', [TimeLogController::class, 'store'])->name('timelog.store');
    Route::post('/switch', [TimeLogController::class, 'switch'])->name('timelog.switch');
    Route::post('/{clockEntry}/update', [TimeLogController::class, 'update'])->name('timelog.update');
    Route::delete('/{clockEntry}/delete', [TimeLogController::class, 'destroy'])->name('timelog.destroy');

});

Route::prefix('projects')->middleware('auth')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

Route::prefix('activities')->middleware('auth')->group(function () {
    Route::get('/', [ActivityController::class, 'index'])->name('activities.index');
    Route::post('/', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/show', [ActivityController::class, 'show'])->name('activities.show');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
