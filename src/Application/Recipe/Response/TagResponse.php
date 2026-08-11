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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}