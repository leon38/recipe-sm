<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Application\Category\Response\CategoryResponse;
use App\Application\Recipe\Response\IngredientResponse;
use App\Application\Recipe\Response\RecipeResponse;
use App\Application\Recipe\Response\StepResponse;
use App\Application\Recipe\Response\TagResponse;
use App\Domain\Recipe\Enum\Difficulty;
use App\Domain\Recipe\Enum\Season;
use App\Domain\Recipe\ValueObject\ValueId;

final class RecipeResponseBuilder extends AbstractBuilder
{
    private string $id;
    private string $title = 'Brownie';
    private string $description = 'Un délicieux brownie';
    private int $prepTime = 15;
    private int $cookTime = 30;
    private int $servings = 4;
    private string $difficulty = Difficulty::EASY->value;
    private string $season = Season::ALL_SEASONS->value;
    private string $sourceUrl = 'https://instagram.com/p/test';
    private string $imageUrl = 'https://picsum.photos/600/400';
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    /** @var IngredientResponse[] */
    private array $ingredients = [];

    /** @var StepResponse[] */
    private array $steps = [];

    /** @var TagResponse[] */
    private array $tags = [];

    /** @var CategoryResponse[] */
    private array $categories = [];

    public function __construct()
    {
        $this->id = (string) ValueId::generate();
        $this->createdAt = new \DateTimeImmutable('2026-07-10T10:59:59');
        $this->updatedAt = new \DateTimeImmutable('2026-07-10T10:59:59');
    }

    public static function create(): self
    {
        return new self();
    }

    public function withTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function withIngredient(string $name, float $quantity = 1, ?string $unit = null): self
    {
        $this->ingredients = $this->append(
            $this->ingredients,
            new IngredientResponse(
                id: (string) ValueId::generate(),
                name: $name,
                quantity: $quantity,
                unit: $unit,
            )
        );

        return $this;
    }

    public function withStep(string $instruction): self
    {
        $this->steps = $this->append(
            $this->steps,
            new StepResponse(
                id: (string) ValueId::generate(),
                position: count($this->steps) + 1,
                instruction: $instruction,
            )
        );

        return $this;
    }

    public function withTag(string $name): self
    {
        $this->tags = $this->append(
            $this->tags,
            new TagResponse(
                id: '1',
                name: $name,
            )
        );

        return $this;
    }

    public function withCategory(string $name): self
    {
        $this->categories = $this->append(
            $this->categories,
            new CategoryResponse(
                id: (string) ValueId::generate(),
                name: $name,
            )
        );

        return $this;
    }

    public function withCreatedAt(?string $datetime = null): self
    {
        $this->createdAt = $datetime ? $this->generateDate($datetime) : $this->now();

        return $this;
    }

    public function withUpdatedAt(?string $datetime = null): self
    {
        $this->updatedAt = $datetime ? $this->generateDate($datetime) : $this->now();

        return $this;
    }

    public function build(): RecipeResponse
    {
        return new RecipeResponse(
            id: $this->id,
            title: $this->title,
            description: $this->description,
            prepTime: $this->prepTime,
            cookTime: $this->cookTime,
            totalTime: $this->cookTime + $this->prepTime,
            servings: $this->servings,
            difficulty: $this->difficulty,
            season: $this->season,
            sourceUrl: $this->sourceUrl,
            imageUrl: $this->imageUrl,
            ingredients: $this->ingredients,
            steps: $this->steps,
            tags: $this->tags,
            categories: $this->categories,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
        );
    }
}
