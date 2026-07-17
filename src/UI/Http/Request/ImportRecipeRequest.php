<?php

namespace App\UI\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ImportRecipeRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Url]
        public string $url,
    ) {
    }
}