<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JenkinsService;

class JenkinsController extends Controller
{
    protected JenkinsService $jenkins;

    public function __construct(JenkinsService $jenkins)
    {
        $this->jenkins = $jenkins;
    }

    /**
     * Trang index bình thường
     */
    public function index()
    {
        // Có thể liệt kê job mặc định, hoặc để input cho người dùng
        $jobs = [
            'MLTrainingDashboard',
            'MyOtherJob'
        ];

        return view('jenkins.index', ['jobs' => $jobs]);
    }

    /**
     * Trigger job và hiển thị log realtime
     */
    public function triggerTerminal(Request $request)
    {
        $jobName = $request->query('job', 'MLTrainingDashboard');
        $this->jenkins->triggerJob($jobName); // trigger job

        $log = $this->jenkins->getJobLog($jobName);

        return view('jenkins.terminal', [
            'jobName' => $jobName,
            'log' => $log
        ]);
    }
}
