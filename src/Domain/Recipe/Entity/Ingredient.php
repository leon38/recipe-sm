<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Ingredient
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'value_id', length: 64)]
        private ValueId $id,
        #[ORM\Column(length: 150)]
        private string $name,
        #[ORM\Column(length: 150)]
        private string $normalizedName,
    ) {
    }

    public static function create(string $name): self
    {
        return new self(
            ValueId::generate(),
            $name,
            strtolower($name),
        );
    }

    public function getId(): ValueId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNormalizedName(): string
    {
        return $this->normalizedName;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setNormalizedName(string $normalizedName): self
    {
        $this->normalizedName = $normalizedName;

        return $this;
    }
}
