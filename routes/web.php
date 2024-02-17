<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DiaryEntryController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConnectionRequestController;


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

Route::get('/privacy', function () {
    return Inertia::render('Privacy');
})->name('privacy');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix('time-logs')->middleware('auth')->group(function () {
    Route::get('/', [TimeLogController::class, 'index'])->name('timelog.index');
    Route::post('/', [TimeLogController::class, 'store'])->name('timelog.store');
    Route::post('/switch', [TimeLogController::class, 'switch'])->name('timelog.switch');
    Route::put('/{timelog}/update', [TimeLogController::class, 'update'])->name('timelog.update');
    Route::put('/batch', [TimeLogController::class, 'batchUpdate'])->name('timelog.batch-update');
    Route::delete('/{timelog}/delete', [TimeLogController::class, 'destroy'])->name('timelog.destroy');

});

Route::prefix('projects')->middleware('auth')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
});

Route::prefix('activities')->middleware('auth')->group(function () {
    Route::get('/', [ActivityController::class, 'index'])->name('activities.index');
    Route::post('/', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/show', [ActivityController::class, 'show'])->name('activities.show');
});

Route::prefix('companies')->middleware('auth')->group(function () {
    Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/show', [CompanyController::class, 'show'])->name('companies.show');
    Route::put('/{company}/update', [CompanyController::class, 'update'])->name('companies.update');
});

Route::prefix('positions')->middleware('auth')->group(function () {
    Route::post('/', [PositionController::class, 'store'])->name('positions.store');
});

Route::prefix('connection-requests')->middleware('auth')->group(function () {
    Route::post('/', [ConnectionRequestController::class, 'store'])->name('connection-requests.store');
    Route::delete('/{connectionRequest}', [ConnectionRequestController::class, 'destroy'])->name('connection-requests.destroy');
    Route::put('/{connectionRequest}', [ConnectionRequestController::class, 'update'])->name('connection-requests.update');
    Route::put('/{connectionRequest}/accept', [ConnectionRequestController::class, 'accept'])->name('connection-requests.accept');
    Route::put('/{connectionRequest}/decline', [ConnectionRequestController::class, 'decline'])->name('connection-requests.decline');
});

Route::prefix('employees')->middleware('auth')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
