<?php

namespace App\Infrastructure\Import;

use App\Application\Recipe\DTO\ImportedRecipeDTO;
use App\Application\Recipe\Service\RecipeImporterInterface;
use App\Infrastructure\Import\Html\HtmlFetcherInterface;
use App\Infrastructure\Import\OpenGraph\OpenGraphExtractorInterface;
use App\Infrastructure\Import\Parser\RecipeParserInterface;

final class InstagramRecipeImporter implements RecipeImporterInterface
{
    public function __construct(
        private HtmlFetcherInterface $htmlFetcher,
        private OpenGraphExtractorInterface $openGraphExtractor,
        private RecipeParserInterface $recipeParser,
    ) {
    }

    public function supports(string $url): bool
    {
        return str_contains($url, 'instagram.com');
    }

    public function import(string $url): ImportedRecipeDTO
    {
        $html = $this->htmlFetcher->fetch($url);

        $og = $this->openGraphExtractor->extract($html);

        $title = nl2br($og->title ?? 'Sans titre');

        $title = mb_substr($title, 0, (strpos($title, "<br />") - 6) ?: 255);

        return $this->recipeParser->parse(
            content: implode("\n", [
                $title,
                $og->description,
            ]),
            sourceUrl: $url,
            imageUrl: $og->image,
        );
        
        return new ImportedRecipeDTO(
            title: $title,
            description: nl2br($og->description ?? ''),
            sourceUrl: $url,
            imageUrl: $og->image,
            ingredients: [],
            steps: [],
        );
    }
}