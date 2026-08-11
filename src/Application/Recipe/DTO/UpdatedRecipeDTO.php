<?php
namespace App\Application\Recipe\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Domain\Recipe\Enum\Difficulty;
use App\Domain\Recipe\Enum\Season;

final readonly class UpdatedRecipeDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $id,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $title,
        public ?string $description,
        public string $sourceUrl,
        public string $imageUrl,
        public ?int $prepTime = null,
        public ?int $cookTime = null,
        #[Assert\Choice(callback: [Difficulty::class, 'cases'])]
        public ?string $difficulty = null,
        public ?int $servings = null,
        #[Assert\Choice(callback: [Season::class, 'cases'])]
        public ?string $season = null,
        
        /** @var UpdatedIngredientDTO[] */
        #[Assert\Length(min: 1)]
        public array $ingredients = [],
        
        /** @var UpdatedStepDTO[] */
        #[Assert\Length(min: 1)]
        public array $steps = [],
        
        /** @var UpdatedTagDTO[] */
        public array $tags = [],
        
        /** @var UpdatedCategoryDTO[] */
        public array $categories = [],
        
    ) {
    }
}