<?php
namespace App\Application\Recipe\Command;


final class ImportRecipeFromUrlCommand
{
    public function __construct(
        public string $url
    ) {}
}