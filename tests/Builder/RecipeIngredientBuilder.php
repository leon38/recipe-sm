<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Recipe\Entity\Ingredient;
use App\Domain\Recipe\Entity\RecipeIngredient;
use App\Domain\Recipe\ValueObject\ValueId;

final class RecipeIngredientBuilder extends AbstractBuilder
{
    private ValueId $id;
    private Ingredient $ingredient;

    private string $name;

    private ?float $quantity = null;

    private ?string $unit = null;

    private function __construct()
    {
        $this->id = $this->generateId();
    }

    public static function create(): self
    {
        return new self();
    }

    public function withIngredient(Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function withUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function build(): RecipeIngredient
    {
        return new RecipeIngredient(
            id: $this->id,
            ingredient: $this->ingredient,
            name: $this->name,
            quantity: $this->quantity,
            unit: $this->unit
        );
    }
}