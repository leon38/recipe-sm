<?php

namespace App\Infrastructure\Import\Parser;

use App\Application\Recipe\DTO\ImportedRecipeDTO;

interface RecipeParserInterface
{
    public function parse(
        string $content,
        string $sourceUrl,
        string $imageUrl,
    ): ImportedRecipeDTO;
}
