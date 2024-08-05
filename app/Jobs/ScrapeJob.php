<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Panther\Client;

class ScrapeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $jobId;
    protected $url;
    protected $selectors;

    public function __construct($jobId, $url, $selectors)
    {
        $this->jobId = $jobId;
        $this->url = $url;
        $this->selectors = $selectors;
    }


    public function handle(): void
    {
        try {
            $options = [
                '--headless',
                '--disable-gpu',
                '--window-size=1920,1080',
                '--no-sandbox',
                '--disable-dev-shm-usage',
                '--remote-debugging-port=9222',
            ];

            $client = Client::createChromeClient(null, $options);
            $crawler = $client->request('GET', $this->url);
            $results = $crawler->filter($this->selectors)->each(function ($node) {
                return $node->text();
            });

            $jobData = json_decode(Redis::get("job:{$this->jobId}"), true);
            $jobData['status'] = 'completed';
            $jobData['results'] = $results;
            Redis::set("job:{$this->jobId}", json_encode($jobData));

            $this->done();

        } catch (Exception $exception) {
            $this->log(sprintf('Job (%s) stopped due to an exception being thrown: "%s"',
                $this->batchId,
                $exception->getMessage()
            ), [
                '$this->batchId' => $this->jobId,
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

