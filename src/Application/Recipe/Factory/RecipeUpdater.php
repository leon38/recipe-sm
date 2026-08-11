<?php

namespace App\Application\Recipe\Factory;

use App\Application\Image\ImageStorageInterface;
use App\Application\Recipe\Command\UpdateRecipeCommand;
use App\Application\Recipe\Resolver\CategoryResolver;
use App\Application\Recipe\Resolver\TagResolver;
use App\Domain\Recipe\Entity\Recipe;

readonly class RecipeUpdater
{
    public function __construct(
        private readonly RecipeIngredientFactory $ingredientFactory,
        private readonly StepFactory $stepFactory,
        private readonly CategoryResolver $categoryResolver,
        private readonly TagResolver $tagResolver,
        private readonly ImageStorageInterface $imageStorage,
    ) {
    }

    public function update(
        Recipe $recipe,
        UpdateRecipeCommand $command,
    ): Recipe {
        $recipe->update(
            title: $command->title,
            description: $command->description,
            prepTime: $command->prepTime,
            cookTime: $command->cookTime,
            difficulty: $command->difficulty,
            servings: $command->servings,
            season: $command->season,
            sourceUrl: $command->sourceUrl
        );

        if ($command->imageUrl !== $recipe->getImageUrl()) {
            $this->imageStorage->delete(
                $recipe->getImageUrl()
            );

            $imageUrl = $this->imageStorage->store(
                $command->imageUrl,
                $recipe->getId()
            );

            $recipe->setImageUrl($imageUrl);
        }

        $recipe->replaceIngredients(
            $this->ingredientFactory->createMany($command->ingredients)
        );

        $recipe->replaceSteps(
            $this->stepFactory->createMany($command->steps)
        );

        $recipe->replaceTags(
            $this->tagResolver->resolve($command->tags)
        );

        $recipe->replaceCategories(
            $this->categoryResolver->resolve($command->categories)
        );

        $recipe->touch();

        return $recipe;
    }
}
