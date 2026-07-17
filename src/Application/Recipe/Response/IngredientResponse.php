<?php

declare(strict_types=1);

namespace App\Application\Recipe\Response;

final readonly class IngredientResponse implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
        public ?float $quantity,
        public ?string $unit,
    ) {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}