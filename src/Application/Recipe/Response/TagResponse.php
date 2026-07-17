<?php

declare(strict_types=1);

namespace App\Application\Recipe\Response;

final readonly class TagResponse implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}