<?php

namespace App\Application\Recipe\CommandHandler;

use App\Application\Recipe\Command\ImportRecipeFromUrlCommand;
use App\Application\Recipe\Service\RecipeImporterRegistry;
use App\Domain\Recipe\Entity\Recipe;
use App\Domain\Recipe\Entity\RecipeIngredient;
use App\Domain\Recipe\Entity\Step;

final class ImportRecipeFromUrlHandler
{
    public function __construct(
        private readonly RecipeImporterRegistry $importerRegistry,
    ) {
    }

    public function __invoke(
        ImportRecipeFromUrlCommand $command,
    ): Recipe {
        $importer = $this->importerRegistry->getImporter($command->url);

        $dto = $importer->import($command->url);

        $recipe = Recipe::create(
            title: $dto->title,
            description: $dto->description,
            prepTime: $dto->prepTime,
            cookTime: $dto->cookTime,
            difficulty: $dto->difficulty,
            servings: $dto->servings ?? 6,
            season: $dto->season,
            sourceUrl: $dto->sourceUrl,
            imageUrl: $dto->imageUrl,
        );

        foreach ($dto->ingredients as $ingredient) {
            $recipe->addIngredient(
                RecipeIngredient::create(
                    name: $ingredient->name,
                    quantity: $ingredient->quantity,
                    unit: $ingredient->unit,
                )
            );
        }

        foreach ($dto->steps as $index => $step) {
            $recipe->addStep(
                Step::create(
                    position: $index + 1,
                    instruction: $step->instruction,
                )
            );
        }

        return $recipe;
    }
}
