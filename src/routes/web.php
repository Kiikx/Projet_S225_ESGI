<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\StatusController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::delete('/projects/{project}/remove-member/{user}', [ProjectController::class, 'removeMember'])->name('projects.removeMember');

    Route::resource('projects.tasks', TaskController::class)->shallow();

    // Administration des labels globaux (accès admin uniquement)
    Route::middleware('admin')->group(function () {
        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/admin/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::get('projects/{project}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::get('projects/{project}/calendar', [ProjectController::class, 'calendar'])->name('projects.calendar');
    Route::put('/tasks/{task}/update-status', [TaskController::class, 'updateStatus']);

    Route::post('/projects/{project}/statuses', [StatusController::class, 'store'])->name('projects.statuses.store');
    Route::delete('/projects/{project}/statuses/{status}', [StatusController::class, 'destroy'])->name('projects.statuses.destroy');

    // Routes pour les invitations par email
    Route::post('/projects/{project}/invite', [\App\Http\Controllers\ProjectInvitationController::class, 'store'])->name('projects.invite');

});

// Routes publiques pour les invitations (pas besoin d'être connecté)
Route::get('/invitations/{token}', [\App\Http\Controllers\ProjectInvitationController::class, 'show'])->name('invitations.show');
Route::post('/invitations/{token}/accept', [\App\Http\Controllers\ProjectInvitationController::class, 'accept'])->name('invitations.accept');
Route::get('/invitations/{token}/accept', [\App\Http\Controllers\ProjectInvitationController::class, 'accept'])->name('invitations.accept.get');
Route::get('/invitations/{token}/decline', [\App\Http\Controllers\ProjectInvitationController::class, 'decline'])->name('invitations.decline');



require __DIR__ . '/auth.php';
