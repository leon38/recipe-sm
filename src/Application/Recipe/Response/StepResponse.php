<?php

declare(strict_types=1);

namespace App\Application\Recipe\Response;

final readonly class StepResponse implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public int $position,
        public string $instruction,
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
