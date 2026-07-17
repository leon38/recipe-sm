<?php
namespace App\Application\Recipe\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdatedStepDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $instruction,
    ) {
    }
}