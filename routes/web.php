<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/resultados', function () {
    return view('results');
})->name('quiniela.results');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/admin/quiniela', function () {
        return view('admin.quiniela-edit');
    })->name('quiniela.edit');

    Route::get('/admin/teams', function () {
        return view('admin.teams');
    })->name('admin-teams');

    Route::get('/admin/matchesresults', function () {
        return view('admin.matches-results');
    })->name('admin-match-results');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
