<?php
namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Category implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'value_id', length: 64)]
    private ValueId $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'categories')]
    private Collection $recipes;

    public function __construct(
        ValueId $id,
        string $name,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->recipes = new ArrayCollection();
    }

    public static function create(string $name): self
    {
        return new self(
            ValueId::generate(),
            $name,
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addCategory($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            $recipe->removeCategory($this);
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
        ];
    }

    public function __toString(): string
    {
        return $this->id->getValue() . ' - ' . $this->name;
    }
}