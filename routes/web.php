<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenkinsController;

Route::get('/', fn() => redirect()->route('jenkins.index'));

// List jobs
Route::get('/jenkins', [JenkinsController::class, 'index'])->name('jenkins.index');

// Trigger job
Route::get('/jenkins/trigger', [JenkinsController::class, 'triggerJob'])->name('jenkins.trigger');

// View log
Route::get('/jenkins/log/{id}', [JenkinsController::class, 'viewLog'])->name('jenkins.log');

// Realtime log (Ajax)
Route::get('/jenkins/log-realtime', [JenkinsController::class, 'realtimeLog'])->name('jenkins.log.realtime');

// Git push
Route::post('/jenkins/git-push', [JenkinsController::class, 'gitPush'])->name('jenkins.git.push');
