<?php

namespace App\Services;

use App\Jobs\ScrapeJob;
use Illuminate\Support\Facades\Redis;

class JobService
{
    public function createJobs(array $data): array
    {
        $jobIds = [];
        foreach ($data['urls'] as $url) {
            $jobId = uniqid('gg', false);
            $jobData = [
                'id' => $jobId,
                'url' => $url,
                'selectors' => $data['selectors'],
                'status' => 'pending',
                'created_at' => now(),
            ];

            Redis::set("job:$jobId", json_encode($jobData));

            ScrapeJob::dispatch($jobId, $url, $data['selectors']);
            $jobIds[] = $jobId;
        }

        return $jobIds;
    }

    public function getJobData($id)
    {
        $jobData = Redis::get("job:$id");
        if (!$jobData) {
            return null;
        }
        return json_decode($jobData, true);
    }

    public function deleteJob($id)
    {
        Redis::delete("job:$id");
    }
}
