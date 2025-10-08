<?php
use Illuminate\Support\Facades\Route;
use App\Services\JenkinsService;

Route::view('/jenkins-dashboard', 'jenkins');

Route::get('/api/jenkins/run', function (JenkinsService $jenkins) {
    $job = request('job', 'MLTrainDB');
    $params = request()->except('job');
    return response()->json($jenkins->triggerJob($job, $params));
});

Route::get('/api/jenkins/status/{job}', function (JenkinsService $jenkins, $job) {
    return response()->json($jenkins->getJobStatus($job));
});

Route::get('/api/jenkins/log/{job}', function (JenkinsService $jenkins, $job) {
    return response($jenkins->getJobLog($job));
});
