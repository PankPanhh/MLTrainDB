<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JenkinsService;
use App\Models\JenkinsJob;

class JenkinsController extends Controller
{
    protected JenkinsService $jenkins;

    public function __construct(JenkinsService $jenkins)
    {
        $this->jenkins = $jenkins;
    }

    public function index()
    {
        $jobs = JenkinsJob::latest()->get();
        return view('jenkins.index', compact('jobs'));
    }

    public function triggerJob(Request $request)
    {
        $jobName = $request->input('job', 'MLTrainingDashboard');
        $triggered = $this->jenkins->triggerJob($jobName);

        $statusData = $this->jenkins->getJobStatus($jobName);
        $logData = $this->jenkins->getJobLog($jobName);

        JenkinsJob::create([
            'job_name' => $jobName,
            'status' => $statusData['result'] ?? 'UNKNOWN',
            'log' => $logData['text'],
            'triggered_at' => now(),
        ]);

        return redirect()->route('jenkins.index')->with('success', "Job $jobName triggered.");
    }

    public function viewLog($id)
    {
        $job = JenkinsJob::findOrFail($id);
        return view('jenkins.job-log', compact('job'));
    }

    // Realtime log: Ajax polling
    public function realtimeLog(Request $request)
    {
        $jobName = $request->input('job', 'MLTrainingDashboard');
        $start = intval($request->input('start', 0));

        return $this->jenkins->getJobLog($jobName, $start);
    }

    // Optional: push code lên Git
    public function gitPush(Request $request)
    {
        $branch = $request->input('branch', 'main');
        $message = $request->input('message', 'Laravel push via JenkinsController');

        exec("git add . && git commit -m '{$message}' && git push origin {$branch} 2>&1", $output, $status);

        return response()->json([
            'status' => $status === 0,
            'output' => implode("\n", $output),
        ]);
    }
}
