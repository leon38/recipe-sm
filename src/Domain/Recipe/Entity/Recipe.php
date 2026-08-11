<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Common\Timestampable;
use App\Domain\Recipe\ValueObject\ValueId;
use App\Domain\Recipe\Repository\DoctrineRecipeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineRecipeRepository::class)]
class Recipe implements \JsonSerializable
{

    use Timestampable;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'value_id', length: 64)]
        private ValueId $id,
        #[ORM\Column(type: 'string', length: 255)]
        private string $title,
        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $description,
        #[ORM\Column(type: 'integer', nullable: true)]
        private ?int $prepTime,
        #[ORM\Column(type: 'integer', nullable: true)]
        private ?int $cookTime,
        #[ORM\Column(type: 'string', length: 100, nullable: true)]
        private ?string $difficulty,
        #[ORM\Column(type: 'integer')]
        private int $servings,
        #[ORM\Column(type: 'string', nullable: true)]
        private ?string $season,
        #[ORM\Column(type: 'string', length: 150, nullable: true)]
        private ?string $sourceUrl,
        #[ORM\Column(type: 'text')]
        private string $imageUrl,
        /** @var Collection<int, RecipeIngredient> */
        #[ORM\OneToMany(
            mappedBy: 'recipe',
            targetEntity: RecipeIngredient::class,
            cascade: ['persist', 'remove'],
            orphanRemoval: true
        )]
        private Collection $ingredients = new ArrayCollection(),
        /** @var Collection<int, Step> */
        #[ORM\OneToMany(targetEntity: Step::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], orphanRemoval: true)]
        private Collection $steps = new ArrayCollection(),
        /** @var Collection<int, Tag> */
        #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'recipes', cascade: ['persist'], orphanRemoval: true)]
        #[ORM\JoinTable(name: 'recipe_tag')]
        private Collection $tags = new ArrayCollection(),
        /** @var Collection<int, Category> */
        #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'recipes', cascade: ['persist'], orphanRemoval: true)]
        #[ORM\JoinTable(name: 'recipe_category')]
        private Collection $categories = new ArrayCollection(),
    ) {
        $this->initializeTimestamps();
    }

    public static function create(
        string $title,
        ?string $description,
        ?int $prepTime,
        ?int $cookTime,
        ?string $difficulty,
        int $servings,
        ?string $season,
        string $sourceUrl,
        string $imageUrl,
    ): self {
        return new self(
            ValueId::generate(),
            $title,
            $description,
            $prepTime,
            $cookTime,
            $difficulty,
            $servings,
            $season,
            $sourceUrl,
            $imageUrl,
        );
    }

    public function update(
        string $title,
        ?string $description,
        ?int $prepTime,
        ?int $cookTime,
        string $difficulty,
        int $servings,
        string $season,
        ?string $sourceUrl,
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->prepTime = $prepTime;
        $this->cookTime = $cookTime;
        $this->difficulty = $difficulty;
        $this->servings = $servings;
        $this->season = $season;
        $this->sourceUrl = $sourceUrl;
    }

    public function getId(): ValueId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrepTime(): ?int
    {
        return $this->prepTime;
    }

    public function setPrepTime(?int $prepTime): self
    {
        $this->prepTime = $prepTime;

        return $this;
    }

    public function getCookTime(): ?int
    {
        return $this->cookTime;
    }

    public function setCookTime(?int $cookTime): self
    {
        $this->cookTime = $cookTime;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getServings(): int
    {
        return $this->servings;
    }

    public function setServings(int $servings): self
    {
        $this->servings = $servings;

        return $this;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(string $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getSourceUrl(): string
    {
        return $this->sourceUrl;
    }

    public function setSourceUrl(string $sourceUrl): self
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(RecipeIngredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            return $this;
        }

        $this->ingredients->add($ingredient);

        $ingredient->setRecipe($this);

        return $this;
    }

    /**
     * @param RecipeIngredient[] $newIngredients
     */
    public function replaceIngredients(array $newIngredients): void
    {
        $existing = [];

        foreach ($this->ingredients as $ingredient) {
            $existing[$ingredient->businessKey()] = $ingredient;
        }

        $processed = [];

        foreach ($newIngredients as $newIngredient) {

            $key = $newIngredient->businessKey();

            if (isset($existing[$key])) {

                $existing[$key]->update(
                    ingredient: $newIngredient->getIngredient(),
                    quantity: $newIngredient->getQuantity(),
                    unit: $newIngredient->getUnit(),
                );

                $processed[$key] = true;

                continue;
            }

            $this->addIngredient($newIngredient);

            $processed[$key] = true;
        }

        foreach ($existing as $key => $ingredient) {

            if (isset($processed[$key])) {
                continue;
            }

            $this->removeIngredient($ingredient);
        }
    }

    /**
     * @param RecipeIngredient[] $ingredients
     */
    public function setIngredients(array $ingredients): self
    {
        $this->ingredients = new ArrayCollection($ingredients);

        return $this;
    }

    public function removeIngredient(RecipeIngredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setRecipe($this);
        }

        return $this;
    }

    /**
     * @param Step[] $steps
     */
    public function replaceSteps(iterable $steps): self
    {
        foreach ($this->steps as $step) {
            $this->removeStep($step);
        }

        foreach ($steps as $step) {
            $this->addStep($step);
        }

        return $this;
    }

    /**
     * @param Step[] $steps
     */
    public function setSteps(array $steps): self
    {
        $this->steps = new ArrayCollection($steps);

        return $this;
    }

    public function removeStep(Step $step): self
    {   
        $this->steps->removeElement($step);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addRecipe($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeRecipe($this);
        }

        return $this;
    }

    /**
     * @param Tag[] $tags
     * @return self
     */
    public function replaceTags(array $tags): self
    {
        foreach ($this->tags as $tag) {
            $this->removeTag($tag);
        }

        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            return $this;
        }

        $this->categories->add($category);

        $category->addRecipe($this);

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if (!$this->categories->removeElement($category)) {
            return $this;
        }

        $category->removeRecipe($this);

        return $this;
    }

    /**
     * @param Category[] $categories
     * @return self
     */
    public function replaceCategories(iterable $categories): self
    {
        foreach ($this->categories as $category) {
            $this->removeCategory($category);
        }

        foreach ($categories as $category) {
            $this->addCategory($category);
        }

        return $this;
    }

    public function findIngredientByIngredientId(ValueId $ingredientId): ?RecipeIngredient
    {
        foreach ($this->ingredients as $recipeIngredient) {
            if ($recipeIngredient->getIngredient()->getId()->equals($ingredientId)) {
                return $recipeIngredient;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {

        return [
            'id' => (string) $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'sourceUrl' => $this->sourceUrl,
            'imageUrl' => $this->imageUrl,
            'prepTime' => $this->prepTime,
            'cookTime' => $this->cookTime,
            'difficulty' => $this->difficulty,
            'ingredients' => array_map(fn(RecipeIngredient $ingredient) => $ingredient->jsonSerialize(), $this->ingredients->toArray()),
            'steps' => array_map(fn(Step $step) => $step->jsonSerialize(), $this->steps->toArray()),
            'tags' => array_map(fn (Tag $tag) => $tag->jsonSerialize(), $this->tags->toArray()),
        ];
    }

    public function __toString(): string
    {
        return $this->id->getValue() . ' - ' . $this->title;
    }

}