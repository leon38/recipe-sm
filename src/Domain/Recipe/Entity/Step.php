<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Step implements \JsonSerializable
{
    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'steps')]
    private Recipe $recipe;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'value_id', length: 64)]
        private ValueId $id,
        #[ORM\Column(type: 'integer')]
        private int $position,
        #[ORM\Column(type: 'text')]
        private string $instruction,
    ) {
    }

    public static function create(
        int $position,
        string $instruction,
    ): self {
        return new self(
            ValueId::generate(),
            $position,
            $instruction,
        );
    }

    public function getId(): ValueId
    {
        return $this->id;
    }

    public function setId(ValueId $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getInstruction(): string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'instruction' => $this->instruction,
        ];
    }
}