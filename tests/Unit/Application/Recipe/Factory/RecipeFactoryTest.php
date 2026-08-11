<?php

declare(strict_types=1);

namespace App\Tests\Application\Recipe\Factory;

use App\Application\Image\ImageStorageInterface;
use App\Application\Recipe\Factory\RecipeFactory;
use App\Application\Recipe\Factory\RecipeIngredientFactory;
use App\Application\Recipe\Factory\StepFactory;
use App\Application\Recipe\Resolver\CategoryResolver;
use App\Application\Recipe\Resolver\IngredientResolver;
use App\Application\Recipe\Resolver\TagResolver;
use App\Infrastructure\Import\Parser\IngredientNameNormalizer;
use App\Tests\ApplicationTestCase;
use App\Tests\Builder\CategoryBuilder;
use App\Tests\Builder\SaveRecipeCommandBuilder;
use App\Tests\Utils\FactoryAssertions;

final class RecipeFactoryTest extends ApplicationTestCase
{
    use FactoryAssertions;

    private RecipeIngredientFactory $ingredientFactory;
    private StepFactory $stepFactory;
    private CategoryResolver $categoryResolver;
    private TagResolver $tagResolver;
    private RecipeFactory $factory;
    private ImageStorageInterface $imageStorage;

    protected function setUp(): void
    {
        parent::setUp();
        $ingredientResolver = new IngredientResolver($this->ingredientRepository, new IngredientNameNormalizer());
        $this->ingredientFactory = new RecipeIngredientFactory($ingredientResolver);
        $this->stepFactory = new StepFactory();
        $this->categoryResolver = new CategoryResolver($this->categoryRepository);
        $this->tagResolver = new TagResolver($this->tagRepository);
        $this->imageStorage = $this->createMock(ImageStorageInterface::class);
        $this->factory = new RecipeFactory(
            $this->ingredientFactory,
            $this->stepFactory,
            $this->categoryResolver,
            $this->tagResolver,
            $this->imageStorage
        );
    }

    public function testCreateRecipe(): void
    {
        $command = SaveRecipeCommandBuilder::create()
            ->withTitle('Brownie')
            ->withDescription('Le meilleur brownie')
            ->build();

        $recipe = $this->factory->create($command);

        self::assertSame('Brownie', $recipe->getTitle());
        self::assertSame('Le meilleur brownie', $recipe->getDescription());
        self::assertSame($command->prepTime, $recipe->getPrepTime());
        self::assertSame($command->cookTime, $recipe->getCookTime());
        self::assertSame($command->difficulty, $recipe->getDifficulty());
        self::assertSame($command->season, $recipe->getSeason());
        self::assertSame($command->servings, $recipe->getServings());
        self::assertSame($command->imageUrl, $recipe->getImageUrl());

        $this->assertGeneratedId($recipe);
        $this->assertInitializedTimestamps($recipe);
    }

    public function testCreateRecipeWithoutDescription(): void
    {
        $command = SaveRecipeCommandBuilder::create()
            ->withDescription(null)
            ->build();

        $recipe = $this->factory->create($command);

        self::assertNull($recipe->getDescription());
        $this->assertGeneratedId($recipe);
        $this->assertInitializedTimestamps($recipe);
    }

    public function testCategoriesCanBeAddedAfterCreation(): void
    {
        $recipe = $this->factory->create(
            SaveRecipeCommandBuilder::create()->build()
        );

        $recipe->addCategory(
            CategoryBuilder::create()
                ->withName('Dessert')
                ->build()
        );

        self::assertCount(1, $recipe->getCategories());
        $this->assertGeneratedId($recipe);
        $this->assertInitializedTimestamps($recipe);
    }
}
