<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Recipe\Entity\Tag;
use App\Domain\Recipe\ValueObject\ValueId;

final class TagBuilder
{
    private int $id;

    private string $name = '#réconfortant';


    private function __construct()
    {
        $this->id = 1;
    }

    public static function create(): self
    {
        return new self();
    }

    public function withId(?int $id = null): self
    {
        $this->id = $id ?? 1;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function build(): Tag
    {
        return new Tag(
            id: $this->id,
            name: $this->name
        );
    }
}