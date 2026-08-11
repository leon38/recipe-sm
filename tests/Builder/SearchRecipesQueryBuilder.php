<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Application\Recipe\Query\SearchRecipesQuery;
use App\Domain\Recipe\Enum\Difficulty;
use App\Domain\Recipe\Enum\Season;

final class SearchRecipesQueryBuilder
{
    private int $page = 1;

    private int $perPage = 20;

    private ?string $search = null;

    /** @var array<string> */
    private array $ingredientIds = [];

    /** @var array<string> */
    private array $categoryIds = [];

    private ?Difficulty $difficulty = null;

    private ?Season $season = null;

    private ?int $maxPrepTime = null;

    public static function create(): self
    {
        return new self();
    }

    public function page(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function search(string $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function withIngredient(string $id): self
    {
        $this->ingredientIds[] = $id;

        return $this;
    }

    public function withCategory(int $id): self
    {
        $this->categoryIds[] = $id;

        return $this;
    }

    public function withDifficulty(Difficulty $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function withSeason(Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function withMaxPrepTime(int $minutes): self
    {
        $this->maxPrepTime = $minutes;

        return $this;
    }

    public function build(): SearchRecipesQuery
    {
        return new SearchRecipesQuery(
            page: $this->page,
            perPage: $this->perPage,
            search: $this->search,
            ingredientIds: $this->ingredientIds,
            categoryIds: $this->categoryIds,
            difficulty: $this->difficulty,
            season: $this->season,
            maxPrepTime: $this->maxPrepTime,
        );
    }
}
