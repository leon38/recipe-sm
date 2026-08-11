<?php

namespace App\Application\Recipe\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdatedTagDTO
{
    public function __construct(
        public ?int $id,
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}
