<?php

namespace App\Application\Recipe\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ImportedIngredientDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\Positive]
        public ?float $quantity = null,
        public ?string $unit = null,
    ) {
    }
}
