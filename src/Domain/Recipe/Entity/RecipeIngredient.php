<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RecipeIngredient implements \JsonSerializable
{
    #[ORM\ManyToOne(
        targetEntity: Recipe::class,
        inversedBy: 'ingredients'
    )]
    private Recipe $recipe;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'value_id', length: 64)]
        private ValueId $id,
        #[ORM\ManyToOne(
            targetEntity: Ingredient::class
        )]
        private Ingredient $ingredient,
        #[ORM\Column(type: 'string', length: 255)]
        private string $name,
        #[ORM\Column(type: 'float', nullable: true)]
        private ?float $quantity,
        #[ORM\Column(type: 'string', length: 100, nullable: true)]
        private ?string $unit,
    ) {
    }

    public static function create(
        string $name,
        ?Ingredient $ingredient = null,
        ?float $quantity = null,
        ?string $unit = null,
    ): self {
        return new self(
            ValueId::generate(),
            $ingredient ?? Ingredient::create($name),
            $name,
            $quantity,
            $unit,
        );
    }

    public function update(
        Ingredient $ingredient,
        ?float $quantity,
        ?string $unit,
    ): void {
        $this->ingredient = $ingredient;
        $this->quantity = $quantity;
        $this->unit = $unit;
    }

    public function ingredientId(): string
    {
        return (string) $this->ingredient->getId();
    }

    public function getId(): ValueId
    {
        return $this->id;
    }

    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function businessKey(): string
    {
        return md5(sprintf(
            '%s|%s|%s',
            $this->ingredient->getId(),
            $this->quantity,
            strtolower($this->unit ?? '')
        ));
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
        ];
    }
}
