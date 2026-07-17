<?php

namespace App\Infrastructure\Import\OpenGraph;

use Symfony\Component\DomCrawler\Crawler;

final class OpenGraphExtractor implements OpenGraphExtractorInterface
{
    public function extract(string $html): OpenGraphData
    {
        $crawler = new Crawler($html);

        $title = $this->getMetaContent($crawler, 'og:title')
            ?? $this->getMetaContent($crawler, 'twitter:title')
            ?? $this->getTitleTag($crawler);

        $description = $this->getMetaContent($crawler, 'og:description')
            ?? $this->getMetaContent($crawler, 'twitter:description')
            ?? null;

        $image = $this->getMetaContent($crawler, 'og:image')
            ?? $this->getMetaContent($crawler, 'twitter:image')
            ?? null;   

        return new OpenGraphData(
            title: $title,
            description: $description,
            image: $image,
        );
    }

    private function getMetaContent(Crawler $crawler, string $property): ?string
    {
        $node = $crawler->filter("meta[property=\"$property\"], meta[name=\"$property\"]");

        if ($node->count() === 0) {
            return null;
        }

        return $node->attr('content');
    }

    private function getTitleTag(Crawler $crawler): ?string
    {
        $node = $crawler->filter('title');

        if ($node->count() === 0) {
            return null;
        }

        return $node->text();
    }
}