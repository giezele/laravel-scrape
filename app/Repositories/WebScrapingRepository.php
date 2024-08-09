<?php

namespace App\Repositories;

use Symfony\Component\Panther\Client;

class WebScrapingRepository
{
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = array_merge([
            '--headless',
            '--disable-gpu',
            '--window-size=1920,1080',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--remote-debugging-port=9222',
        ], $options);
    }

    public function scrape(string $url, string $selectors): array
    {
        $client = Client::createChromeClient(null, $this->options);
        $crawler = $client->request('GET', $url);
        return $crawler->filter($selectors)->each(function ($node) {
            return $node->text();
        });
    }
}
