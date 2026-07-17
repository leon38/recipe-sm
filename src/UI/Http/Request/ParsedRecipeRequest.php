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