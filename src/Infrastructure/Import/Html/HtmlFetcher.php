<?php

namespace App\Infrastructure\Import\Html;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HtmlFetcher implements HtmlFetcherInterface
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function fetch(string $url): string
    {
        $response = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; RecipeBot/1.0)',
                'Accept' => 'text/html,application/xhtml+xml',
            ],
            'timeout' => 10,
        ]);

        return $response->getContent();
    }
}