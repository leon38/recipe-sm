<?php
namespace App\Application\Recipe\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ImportedRecipeDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $title,
        public ?string $description,
        public string $sourceUrl,
        public string $imageUrl,
        public ?int $prepTime = null,
        public ?int $cookTime = null,
        #[Assert\Choice(callback: [\App\Domain\Recipe\Enum\Difficulty::class, 'cases'])]
        public ?string $difficulty = null,
        public ?int $servings = null,
        #[Assert\Choice(callback: [\App\Domain\Recipe\Enum\Season::class, 'cases'])]
        public ?string $season = null,
        
        /** @var ImportedIngredientDTO[] */
        #[Assert\Length(min: 1)]
        public array $ingredients = [],
        
        /** @var ImportedStepDTO[] */
        #[Assert\Length(min: 1)]
        public array $steps = [],
        
        /** @var ImportedTagDTO[] */
        public array $tags = [],
        
        /** @var ImportedCategoryDTO[] */
        public array $categories = [],
        
    ) {
    }
}