<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Projects\Create as ProjectsCreate;
use App\Livewire\Projects\Index as ProjectsIndex;
use App\Livewire\Projects\Show as ProjectsShow;
use App\Livewire\Subtasks\Create as SubtasksCreate;
use App\Livewire\Subtasks\Index as SubtasksIndex;
use App\Livewire\Tasks\Create as TasksCreate;
use App\Livewire\Tasks\Index as TasksIndex;
use App\Livewire\Users\Edit as UsersEdit;
use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->guard()->check()) {
        return redirect('/login');
    }

    // Staff users should go to tasks, not projects
    if (auth()->guard()->user()->isStaff()) {
        return redirect('/tasks');
    }

    return redirect('/projects');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    // Projects routes
    Route::get('/projects', ProjectsIndex::class)->name('projects.index');
    Route::get('/projects/create', ProjectsCreate::class)->name('projects.create');
    Route::get('/projects/{project}', ProjectsShow::class)->name('projects.show');

    // Tasks routes
    Route::get('/tasks', TasksIndex::class)->name('tasks.index');
    Route::get('/tasks/create', TasksCreate::class)->name('tasks.create');

    // Subtasks routes
    Route::get('/subtasks', SubtasksIndex::class)->name('subtasks.index');
    Route::get('/subtasks/create', SubtasksCreate::class)->name('subtasks.create');

    // Users routes
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/users/create', UsersEdit::class)->name('users.create');
    Route::get('/users/{user}/edit', UsersEdit::class)->name('users.edit');
});
