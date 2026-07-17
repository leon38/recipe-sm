<?php
namespace App\Application\Recipe\Resolver;

use App\Domain\Recipe\Entity\Ingredient;
use App\Domain\Recipe\Repository\IngredientRepositoryInterface;
use App\Infrastructure\Import\Parser\IngredientNameNormalizer;

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

        if ($ingredient !== null) {
            return $ingredient;
        }

        $ingredient = Ingredient::create(
            name: $name,
        );

        $this->repository->save($ingredient);

        return $ingredient;
    }
}