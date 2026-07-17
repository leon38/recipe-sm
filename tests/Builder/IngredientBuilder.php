<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Recipe\Entity\Ingredient;
use App\Domain\Recipe\ValueObject\ValueId;

final class IngredientBuilder extends AbstractBuilder
{
    private ValueId $id;

    private string $name = 'Farine';

    private string $normalizedName = 'farine';

    private function __construct()
    {
        $this->id = $this->generateId();
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

    public function withNormalizedName(string $normalizedName): self
    {
        $this->normalizedName = $normalizedName;

        return $this;
    }

    public function build(): Ingredient
    {
        return new Ingredient(
            id: $this->id,
            name: $this->name,
            normalizedName: $this->normalizedName,
        );
    }
}