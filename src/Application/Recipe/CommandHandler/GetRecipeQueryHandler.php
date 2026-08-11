<?php

namespace App\Application\Recipe\CommandHandler;

use App\Application\Recipe\Mapper\RecipeResponseMapper;
use App\Application\Recipe\Query\GetRecipeQuery;
use App\Application\Recipe\Response\RecipeResponse;
use App\Domain\Recipe\Repository\RecipeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class GetRecipeQueryHandler
{
    public function __construct(
        private RecipeRepositoryInterface $recipeRepository,
        private RecipeResponseMapper $recipeResponseMapper,
    ) {
    }

    public function __invoke(GetRecipeQuery $query): RecipeResponse
    {
        $recipe = $this->recipeRepository->get($query->id);

        return $this->recipeResponseMapper->map($recipe);
    }
}
