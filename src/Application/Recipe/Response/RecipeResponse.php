<?php

declare(strict_types=1);

namespace App\Application\Recipe\Response;

use App\Application\Category\Response\CategoryResponse;

final readonly class RecipeResponse implements \JsonSerializable
{
    /**
     * @param IngredientResponse[] $ingredients
     * @param StepResponse[]       $steps
     * @param TagResponse[]        $tags
     * @param CategoryResponse[]   $categories
     */
    public function __construct(
        public string $id,
        public string $title,
        public ?string $description,
        public ?int $prepTime,
        public ?int $cookTime,
        public ?int $totalTime,
        public ?string $difficulty,
        public ?int $servings,
        public ?string $season,
        public ?string $sourceUrl,
        public string $imageUrl,
        public array $ingredients,
        public array $steps,
        public array $tags,
        public array $categories,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
