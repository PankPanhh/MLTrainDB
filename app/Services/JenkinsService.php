<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JenkinsService
{
    protected $baseUrl;
    protected $user;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.jenkins.url');
        $this->user = config('services.jenkins.user');
        $this->token = config('services.jenkins.token');
    }

    public function triggerJob($jobName, $params = [])
    {
        $url = "{$this->baseUrl}/job/{$jobName}/build";

        // Nếu có parameters, dùng endpoint /buildWithParameters
        if (!empty($params)) {
            $url = "{$this->baseUrl}/job/{$jobName}/buildWithParameters";
        }

        try {
            $response = Http::withBasicAuth($this->user, $this->token)
                ->asForm()
                ->post($url, $params);

            if ($response->successful()) {
                return ['status' => 'ok', 'message' => "Job '{$jobName}' triggered"];
            }

            return ['status' => 'error', 'message' => $response->body()];
        } catch (\Throwable $e) {
            Log::error("Jenkins Trigger Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getJobStatus($jobName)
    {
        $url = "{$this->baseUrl}/job/{$jobName}/lastBuild/api/json";

        try {
            $response = Http::withBasicAuth($this->user, $this->token)->get($url);

            if (!$response->successful()) {
                return ['status' => 'error', 'message' => $response->body()];
            }

            $data = $response->json();

            return [
                'status' => 'ok',
                'building' => $data['building'] ?? false,
                'result' => $data['result'] ?? 'UNKNOWN',
                'timestamp' => $data['timestamp'] ?? null,
                'duration' => $data['duration'] ?? null
            ];
        } catch (\Throwable $e) {
            Log::error("Jenkins Status Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getJobLog($jobName)
    {
        $url = "{$this->baseUrl}/job/{$jobName}/lastBuild/logText/progressiveText";

        try {
            $response = Http::withBasicAuth($this->user, $this->token)->get($url);

            if (!$response->successful()) {
                return "Error getting log: " . $response->body();
            }

            return $response->body();
        } catch (\Throwable $e) {
            Log::error("Jenkins Log Error: " . $e->getMessage());
            return "Exception: " . $e->getMessage();
        }
    }
}
