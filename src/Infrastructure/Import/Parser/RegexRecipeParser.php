<?php

namespace App\Infrastructure\Import\Parser;

use App\Application\Recipe\DTO\ImportedRecipeDTO;

final class RegexRecipeParser implements RecipeParserInterface
{

    public function __construct(
        private IngredientExtractor $ingredientExtractor,
        private StepExtractor $stepExtractor,
        private TagExtractor $tagExtractor,
    ) {
    }

    public function parse(
        string $content,
        string $sourceUrl,
        ?string $imageUrl = null,
    ): ImportedRecipeDTO {

        $content = $this->removeEmojis($content);

        $title = $this->extractTitle($content);

        $dto = new ImportedRecipeDTO(
            title: $title,
            description: $content,
            sourceUrl: $sourceUrl,
            imageUrl: $imageUrl ?? '',
            ingredients: $this->ingredientExtractor->extract($content),
            steps: $this->stepExtractor->extract($content),
            tags: $this->tagExtractor->extract($content),
        );

        return $dto;
    }

    private function extractTitle(string $content): string
    {
        $lines = preg_split('/\R/u', $content);

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            return mb_substr($line, 0, 255);
        }

        return 'Sans titre';
    }

    private function removeEmojis(string $text): string
    {
        return preg_replace(
            '/[\p{So}\p{Sk}]/u',
            '',
            $text
        );
    }
}