<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use \App\Http\Controllers\CategoryController;

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

    Route::post('/projects/{project}/add-member', [ProjectController::class, 'addMember'])->name('projects.addMember');
    Route::delete('/projects/{project}/remove-member/{user}', [ProjectController::class, 'removeMember'])->name('projects.removeMember');

    Route::resource('projects.tasks', TaskController::class)->shallow();

    Route::post('/projects/{project}/categories', [CategoryController::class, 'store'])->name('projects.categories.store');
    Route::delete('/projects/{project}/categories/{category}', [CategoryController::class, 'destroy'])->name('projects.categories.destroy');

});



require __DIR__ . '/auth.php';
