<?php

namespace App\Application\Recipe\Service;

use App\Application\Recipe\DTO\ImportedRecipeDTO;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'app.recipe_importer')]
interface RecipeImporterInterface
{
    public function supports(string $url): bool;

    public function import(string $url): ImportedRecipeDTO;
}
