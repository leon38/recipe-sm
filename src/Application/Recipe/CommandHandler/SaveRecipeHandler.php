<?php

namespace App\Application\Recipe\CommandHandler;

use App\Application\Recipe\Command\SaveRecipeCommand;
use App\Application\Recipe\Factory\RecipeFactory;
use App\Domain\Recipe\Entity\Recipe;
use App\Domain\Recipe\Repository\RecipeRepositoryInterface;

final class SaveRecipeHandler
{
    public function __construct(
        private readonly RecipeFactory $factory,
        private readonly RecipeRepositoryInterface $repository,
    ) {
    }

    public function __invoke(SaveRecipeCommand $command): Recipe
    {
        $recipe = $this->factory->create($command);

        $this->repository->save($recipe);

        return $recipe;
    }
}
