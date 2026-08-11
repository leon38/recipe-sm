<?php

namespace App\UI\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ParsedRecipeRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,
        public ?string $description,
        public ?int $prepTime,
        public ?int $cookTime,
        public ?string $difficulty,
        #[Assert\NotBlank]
        public string $imageUrl,
        #[Assert\NotBlank]
        public string $sourceUrl,
        public ?int $servings,
        public ?string $season,
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
