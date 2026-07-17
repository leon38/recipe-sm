<?php
namespace App\Application\Recipe\Query;

use App\Application\Recipe\Response\PaginatedResponse;

interface RecipeReadRepositoryInterface
{

    public function search(SearchRecipesQuery $query): PaginatedResponse;

}