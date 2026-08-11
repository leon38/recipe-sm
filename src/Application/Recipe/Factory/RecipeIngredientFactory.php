<?php

namespace App\Application\Recipe\Factory;

use App\Application\Recipe\Resolver\IngredientResolver;
use App\Domain\Recipe\Entity\RecipeIngredient;

final readonly class RecipeIngredientFactory
{
    public function __construct(
        private IngredientResolver $ingredientResolver,
    ) {
    }

    /**
     * @param array<int,array<string,mixed>> $ingredients
     *
     * @return RecipeIngredient[]
     */
    public function createMany(array $ingredients): array
    {
        $recipeIngredients = [];

        foreach ($ingredients as $ingredientData) {
            $ingredient = $this->ingredientResolver->resolve(
                $ingredientData['name']
            );

            $recipeIngredients[] = RecipeIngredient::create(
                name: $ingredientData['name'],
                ingredient: $ingredient,
                quantity: is_string($ingredientData['quantity']) ? (float) $ingredientData['quantity'] : $ingredientData['quantity'],
                unit: $ingredientData['unit'],
            );
        }

        return $recipeIngredients;
    }
}
