<?php
namespace App\Application\Recipe\Command;

final class SaveRecipeCommand
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?int $prepTime,
        public ?int $cookTime,
        public ?string $difficulty,
        public ?int $servings,
        public ?string $season,
        public string $imageUrl,
        public string $sourceUrl,
        /** @var ImportedIngredientDTO[] */
        public array $ingredients,
        /** @var ImportedStepDTO[] */
        public array $steps,
        /** @var ImportedTagDTO[] */
        public array $tags = [],
        /** @var ImportedCategoryDTO[] */
        public array $categories = [],
    ) {

    }
}