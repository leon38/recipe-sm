<?php
namespace App\Application\Recipe\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class ImportedCategoryDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}