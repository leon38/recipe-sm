<?php

namespace App\Infrastructure\Import\OpenGraph;

final readonly class OpenGraphData
{
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $image,
    ) {
    }
}