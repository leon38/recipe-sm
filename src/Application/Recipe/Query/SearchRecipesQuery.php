<?php

namespace App\Application\Recipe\Query;

use App\Domain\Recipe\Enum\Difficulty;
use App\Domain\Recipe\Enum\Season;

final class SearchRecipesQuery
{
    public function __construct(
        public ?int $page = 1,
        public ?int $perPage = 20,
        public ?string $search = null,
        /** @var string[] */
        public array $ingredientIds = [],
        /** @var string[] */
        public array $categoryIds = [],

        public ?Difficulty $difficulty = null,

        public ?Season $season = null,
        public ?int $maxPrepTime = null,
    ) {
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }
}
