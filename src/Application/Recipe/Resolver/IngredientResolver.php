<?php

namespace App\Application\Recipe\Resolver;

use App\Domain\Recipe\Entity\Ingredient;
use App\Domain\Recipe\Repository\IngredientRepositoryInterface;
use App\Domain\Recipe\Service\IngredientNameNormalizer;

final readonly class IngredientResolver
{
    public function __construct(
        private IngredientRepositoryInterface $repository,
        private IngredientNameNormalizer $ingredientNormalizer,
    ) {
    }

    public function resolve(string $name): Ingredient
    {
        $normalizedName = $this->ingredientNormalizer->normalize($name);

        $ingredient = $this->repository->findOneByNormalizedName($normalizedName);

        if (null !== $ingredient) {
            return $ingredient;
        }

        $ingredient = Ingredient::create(
            name: $name,
            normalizedName: $normalizedName,
        );

        $this->repository->save($ingredient);

        return $ingredient;
    }
}
