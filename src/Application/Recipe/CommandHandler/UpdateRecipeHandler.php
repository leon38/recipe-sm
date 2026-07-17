<?php
namespace App\Application\Recipe\CommandHandler;

use App\Application\Recipe\Factory\RecipeUpdater;
use App\Application\Recipe\Command\UpdateRecipeCommand;
use App\Domain\Recipe\Entity\Recipe;
use App\Domain\Recipe\ValueObject\ValueId;
use App\Domain\Recipe\Repository\RecipeRepositoryInterface;

final class UpdateRecipeHandler
{
    public function __construct(
        private readonly RecipeUpdater $updater,
        private readonly RecipeRepositoryInterface $repository,
    ) {}

    public function __invoke(UpdateRecipeCommand $command): Recipe
    {
        $recipe = $this->repository->get(new ValueId($command->id));
        $recipe = $this->updater->update($recipe, $command);

        $this->repository->save($recipe);

        return $recipe;
    }
}