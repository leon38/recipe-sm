<?php
namespace App\Application\Recipe\Command;


final class UpdateRecipeCommand
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $description,
        public ?int $prepTime,
        public ?int $cookTime,
        public ?string $difficulty,
        public ?int $servings,
        public ?string $season,
        public string $imageUrl,
        public string $sourceUrl,
        /** @var array<int,array<string,mixed>> */
        public array $ingredients,
        /** @var array<int,array<string,mixed>> */
        public array $steps,
        /** @var array<int,array<string,mixed>> */
        public array $tags = [],
        /** @var array<int,array<string,mixed>> */
        public array $categories = [],
    ) {

    }
}