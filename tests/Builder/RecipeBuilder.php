<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Recipe\Entity\Category;
use App\Domain\Recipe\Entity\Recipe;
use App\Domain\Recipe\Entity\RecipeIngredient;
use App\Domain\Recipe\Entity\Step;
use App\Domain\Recipe\Entity\Tag;
use App\Domain\Recipe\Enum\Difficulty;
use App\Domain\Recipe\Enum\Season;
use App\Domain\Recipe\ValueObject\ValueId;

final class RecipeBuilder extends AbstractBuilder
{
    private ValueId $id;

    private string $title = 'Brownie';

    private ?string $description = 'Une délicieuse recette';

    private int $prepTime = 15;

    private int $cookTime = 30;

    private int $servings = 4;

    private string $difficulty = Difficulty::EASY->value;

    private string $season = Season::ALL_SEASONS->value;

    private ?string $sourceUrl = 'https://instagram.com/p/123';

    private string $imageUrl = 'https://picsum.photos/600/400';

    /** @var RecipeIngredient[] */
    private array $ingredients = [];

    /** @var Step[] */
    private array $steps = [];

    /** @var Tag[] */
    private array $tags = [];

    /** @var Category[] */
    private array $categories = [];

    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

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

    public function withTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function withDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function withPrepTime(int $minutes): self
    {
        $this->prepTime = $minutes;

        return $this;
    }

    public function withCookTime(int $minutes): self
    {
        $this->cookTime = $minutes;

        return $this;
    }

    public function withServings(int $servings): self
    {
        $this->servings = $servings;

        return $this;
    }

    public function withDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function withSeason(string $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function withImage(string $image): self
    {
        $this->imageUrl = $image;

        return $this;
    }

    public function withSourceUrl(?string $url): self
    {
        $this->sourceUrl = $url;

        return $this;
    }

    public function withIngredient(RecipeIngredient $ingredient): self
    {
        $this->ingredients = $this->append(
            $this->ingredients,
            $ingredient
        );

        return $this;
    }

    public function withStep(Step $step): self
    {
        $this->steps = $this->append(
            $this->steps,
            $step
        );

        return $this;
    }

    public function withTag(Tag $tag): self
    {
        $this->tags = $this->append(
            $this->tags,
            $tag
        );

        return $this;
    }

    public function withCategory(Category $category): self
    {
        $this->categories = $this->append(
            $this->categories,
            $category
        );

        return $this;
    }

    public function withCreatedAt(?string $datetime): self
    {
        $this->createdAt = $datetime ? $this->generateDate($datetime) : $this->now();

        return $this;
    }

    public function withUpdatedAt(?string $datetime): self
    {
        $this->updatedAt = $datetime ? $this->generateDate($datetime) : $this->now();

        return $this;
    }

    public function build(): Recipe
    {
        $recipe = new Recipe(
            id: $this->id,
            title: $this->title,
            description: $this->description,
            prepTime: $this->prepTime,
            cookTime: $this->cookTime,
            difficulty: $this->difficulty,
            servings: $this->servings,
            season: $this->season,
            sourceUrl: $this->sourceUrl,
            imageUrl: $this->imageUrl,
        );

        foreach ($this->ingredients as $ingredient) {
            $recipe->addIngredient($ingredient);
        }

        foreach ($this->steps as $step) {
            $recipe->addStep($step);
        }

        foreach ($this->tags as $tag) {
            $recipe->addTag($tag);
        }

        foreach ($this->categories as $category) {
            $recipe->addCategory($category);
        }

        $recipe->setCreatedAt($this->createdAt ?? $this->now());
        $recipe->setUpdatedAt($this->updatedAt ?? $this->now());

        return $recipe;
    }
}
