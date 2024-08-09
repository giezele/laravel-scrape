<?php

namespace App\Jobs;

use App\Enums\JobStatusEnum;
use App\Repositories\JobRepository;
use App\Repositories\WebScrapingRepository;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScrapeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    private JobRepository $jobRepository;

    private WebScrapingRepository $webScrapingRepository;

    public function __construct(
        private string $jobId,
        private string $url,
        private string $selectors
    ) {
        $this->jobRepository = new JobRepository($this->jobId);
        $this->webScrapingRepository = new WebScrapingRepository();
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            $results = $this->webScrapingRepository->scrape($this->url, $this->selectors);
            $this->jobRepository->saveResults($results);
            $this->jobRepository->updateJobStatus(JobStatusEnum::COMPLETED->value);

            $this->done();
        } catch (Exception $exception) {
            $this->log(sprintf('Job (%s) stopped due to an exception being thrown: "%s"',
                $this->batchId,
                $exception->getMessage()
            ), [
                'jobId' => $this->jobId,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            $this->fail($exception);
        }
    }

    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    private function log(string $message, array $context = []): void
    {
        Log::info(sprintf('[%s]-[%s] :: %s',
            $this->jobId,
            ($this->job?->getConnectionName() ?? '??'),
            $message),
            $context
        );
    }

    /**
     * @return void
     */
    private function done(): void
    {
        $this->log('Done!');
    }
}

