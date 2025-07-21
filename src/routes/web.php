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

    Route::post('/projects/{project}/add-member', [ProjectController::class, 'addMember'])->name('projects.addMember');
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

});



require __DIR__ . '/auth.php';
