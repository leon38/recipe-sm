<?php
namespace App\Application\Recipe\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdatedCategoryDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}