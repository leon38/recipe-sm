<?php
namespace App\Application\Recipe\Factory;

use App\Application\Image\ImageStorageInterface;
use App\Application\Recipe\Command\SaveRecipeCommand;
use App\Application\Recipe\Resolver\CategoryResolver;
use App\Application\Recipe\Resolver\TagResolver;
use App\Domain\Recipe\Entity\Recipe;

readonly class RecipeFactory
{
    public function __construct(
        private RecipeIngredientFactory $ingredientFactory,
        private StepFactory $stepFactory,
        private CategoryResolver $categoryResolver,
        private TagResolver $tagResolver,
        private readonly ImageStorageInterface $imageStorage,
    ) {
    }

    public function create(SaveRecipeCommand $command): Recipe
    {
        $recipe = Recipe::create(
            title: $command->title,
            description: $command->description,
            prepTime: $command->prepTime,
            cookTime: $command->cookTime,
            difficulty: $command->difficulty,
            servings: $command->servings,
            season: $command->season,
            sourceUrl: $command->sourceUrl,
            imageUrl: "",
        );

        $imageUrl = $this->imageStorage->store(
            $command->imageUrl,
            $recipe->getId()
        );

        $recipe->setImageUrl($imageUrl);

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

        return $recipe;
    }
}