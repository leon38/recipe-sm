<?php
namespace App\Application\Recipe\CommandHandler;

use App\Application\Recipe\Response\PaginatedResponse;
use App\Application\Recipe\Query\RecipeReadRepositoryInterface;
use App\Application\Recipe\Query\SearchRecipesQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class SearchRecipesQueryHandler
{
    public function __construct(
        private RecipeReadRepositoryInterface $recipeRepository,
    )
    {}

    public function __invoke(SearchRecipesQuery $query): PaginatedResponse
    {
        return $this->recipeRepository->search($query);
    }
}