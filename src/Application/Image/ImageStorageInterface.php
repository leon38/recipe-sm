<?php

namespace App\Application\Image;

use App\Domain\Recipe\ValueObject\ValueId;

interface ImageStorageInterface
{
    public function store(string $image, ValueId $id): string;

    public function exists(string $url): bool;

    public function delete(string $url): void;
}
