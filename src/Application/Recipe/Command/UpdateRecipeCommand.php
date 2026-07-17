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
        /** @var UpdatedIngredientDTO[] */
        public array $ingredients,
        /** @var UpdatedStepDTO[] */
        public array $steps,
        /** @var UpdatedTagDTO[] */
        public array $tags = [],
        /** @var UpdatedCategoryDTO[] */
        public array $categories = [],
    ) {

    }
}