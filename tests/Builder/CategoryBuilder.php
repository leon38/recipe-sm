<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Recipe\Entity\Category;
use App\Domain\Recipe\ValueObject\ValueId;

final class CategoryBuilder extends AbstractBuilder
{
    private ValueId $id;

    private string $name = 'Dessert';

    private function __construct()
    {
        $this->id = ValueId::generate();
    }

    public static function create(): self
    {
        return new self();
    }

    public function withId(?ValueId $id = null): self
    {
        $this->id = $id ?? $this->generateId();

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function build(): Category
    {
        return new Category(
            id: $this->id,
            name: $this->name
        );
    }
}
