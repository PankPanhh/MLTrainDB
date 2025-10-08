<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class JenkinsService
{
    protected string $baseUrl;
    protected string $user;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.jenkins.url');
        $this->user = config('services.jenkins.user');
        $this->token = config('services.jenkins.token');
    }

    protected function getCrumb(): string
    {
        $response = Http::withBasicAuth($this->user, $this->token)
                        ->get("{$this->baseUrl}/crumbIssuer/api/json");

        return $response->json()['crumb'] ?? '';
    }

    public function triggerJob(string $jobName): bool
    {
        $crumb = $this->getCrumb();
        $response = Http::withBasicAuth($this->user, $this->token)
                        ->withHeaders(['Jenkins-Crumb' => $crumb])
                        ->post("{$this->baseUrl}/job/{$jobName}/build");

        return $response->successful();
    }

    public function getJobStatus(string $jobName): array
    {
        $response = Http::withBasicAuth($this->user, $this->token)
                        ->get("{$this->baseUrl}/job/{$jobName}/lastBuild/api/json");

        return $response->json() ?? [];
    }

    public function getJobLog(string $jobName, int $start = 0): array
    {
        $url = "{$this->baseUrl}/job/{$jobName}/lastBuild/logText/progressiveText?start={$start}";
        $response = Http::withBasicAuth($this->user, $this->token)->get($url);
        $text = $response->body();
        $size = intval($response->header('X-Text-Size', 0));
        $more = filter_var($response->header('X-More-Data', false), FILTER_VALIDATE_BOOLEAN);

        return [
            'text' => $text,
            'size' => $size,
            'more' => $more,
        ];
    }
}
