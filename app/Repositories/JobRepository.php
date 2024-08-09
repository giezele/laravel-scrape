<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class JobRepository
{
    public function __construct(private string $jobId) {}

    public function getJobData(): array
    {
        return json_decode(Redis::get("job:{$this->jobId}"), true);
    }

    public function updateJobStatus(string $status): void
    {
        $jobData = $this->getJobData();
        $jobData['status'] = $status;
        Redis::set("job:{$this->jobId}", json_encode($jobData));
    }

    public function saveResults(array $results): void
    {
        $jobData = $this->getJobData();
        $jobData['results'] = $results;
        Redis::set("job:{$this->jobId}", json_encode($jobData));
    }
}
