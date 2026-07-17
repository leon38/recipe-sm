<?php
namespace App\Tests\Builder;

use App\Application\Recipe\Command\UpdateRecipeCommand;
use App\Domain\Recipe\ValueObject\ValueId;
use App\Domain\Recipe\Enum\Difficulty;
use App\Domain\Recipe\Enum\Season;

final class UpdateRecipeCommandBuilder
{
    private string $id;
    protected string $title = 'Brownie';
    protected ?string $description = 'Délicieux brownie';
    public int $prepTime = 15;
    public int $cookTime = 30;
    public int $servings = 4;
    protected string $difficulty = Difficulty::EASY->value;
    protected string $season = Season::ALL_SEASONS->value;
    protected ?string $sourceUrl = 'https://instagram.com/p/123';
    protected string $imageUrl = 'https://picsum.photos/600/400';

    protected array $ingredients = [];
    protected array $steps = [];
    protected array $tags = [];
    protected array $categories = [];

    public function __construct()
    {
        $this->id = (string) ValueId::generate();
    }

    public static function create(): self
    {
        return new self();
    }

    public function withId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function withTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function withDescription(string $description): self
    {
        $this->description = $description;
        
        return $this;
    }

    public function withIngredient(string $name, float $quantity, ?string $unit = null): self
    {
        $this->ingredients[] = [
            'name' => $name,
            'quantity' => $quantity,
            'unit' => $unit,
        ];

        return $this;
    }

    public function withStep(string $instruction): self
    {
        $this->steps[] = [
            'instruction' => $instruction,
        ];

        return $this;
    }

    public function withTag(string $name): self
    {
        $this->tags[] = [
            'name' => $name,
        ];

        return $this;
    }

    public function withCategory(int $id): self
    {
        $this->categories[] = $id;

        return $this;
    }

    public function build(): UpdateRecipeCommand
    {
        return new UpdateRecipeCommand(
            id: $this->id,
            title: $this->title,
            description: $this->description,
            prepTime: $this->prepTime,
            cookTime: $this->cookTime,
            servings: $this->servings,
            difficulty: $this->difficulty,
            season: $this->season,
            sourceUrl: $this->sourceUrl,
            imageUrl: $this->imageUrl,
            ingredients: $this->ingredients,
            steps: $this->steps,
            tags: $this->tags,
            categories: $this->categories,
        );
    }

}