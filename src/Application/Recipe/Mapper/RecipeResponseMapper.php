<?php

declare(strict_types=1);

namespace App\Application\Recipe\Mapper;

use App\Application\Category\Response\CategoryResponse;
use App\Application\Recipe\Response\IngredientResponse;
use App\Application\Recipe\Response\RecipeResponse;
use App\Application\Recipe\Response\StepResponse;
use App\Application\Recipe\Response\TagResponse;
use App\Domain\Recipe\Entity\Recipe;
use Symfony\Component\HttpFoundation\UrlHelper;

final class RecipeResponseMapper
{
    public function __construct(
        private UrlHelper $urlHelper,
    ) {
    }

    public function map(Recipe $recipe): RecipeResponse
    {
        return new RecipeResponse(
            id: (string) $recipe->getId(),
            title: $recipe->getTitle(),
            description: $recipe->getDescription(),
            sourceUrl: $recipe->getSourceUrl(),
            imageUrl: '' !== $recipe->getImageUrl() ? $this->urlHelper->getAbsoluteUrl($recipe->getImageUrl()) : '',
            prepTime: $recipe->getPrepTime(),
            cookTime: $recipe->getCookTime(),
            totalTime: $recipe->getPrepTime() + $recipe->getCookTime(),
            difficulty: $recipe->getDifficulty(),
            servings: $recipe->getServings(),
            season: $recipe->getSeason(),
            ingredients: array_map(
                static fn ($ingredient) => new IngredientResponse(
                    id: (string) $ingredient->getId(),
                    name: $ingredient->getName(),
                    quantity: $ingredient->getQuantity(),
                    unit: $ingredient->getUnit(),
                ),
                $recipe->getIngredients()->toArray(),
            ),
            steps: array_map(
                static fn ($step) => new StepResponse(
                    id: (string) $step->getId(),
                    position: $step->getPosition(),
                    instruction: $step->getInstruction(),
                ),
                $recipe->getSteps()->toArray(),
            ),
            tags: array_map(
                static fn ($tag) => new TagResponse(
                    id: (string) $tag->getId(),
                    name: $tag->getName(),
                ),
                $recipe->getTags()->toArray(),
            ),
            categories: array_map(
                static fn ($category) => new CategoryResponse(
                    id: (string) $category->getId(),
                    name: $category->getName(),
                ),
                $recipe->getCategories()->toArray(),
            ),
            createdAt: $recipe->getCreatedAt(),
            updatedAt: $recipe->getUpdatedAt(),
        );
    }
}
