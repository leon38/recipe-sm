<?php

declare(strict_types=1);

namespace Tests\Application\Recipe\Factory;

use App\Application\Recipe\Factory\RecipeIngredientFactory;
use App\Application\Recipe\Resolver\IngredientResolver;
use App\Infrastructure\Import\Parser\IngredientNameNormalizer;
use App\Tests\ApplicationTestCase;
use App\Tests\Builder\IngredientBuilder;

final class RecipeIngredientFactoryTest extends ApplicationTestCase
{
    private RecipeIngredientFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $ingredientResolver = new IngredientResolver($this->ingredientRepository, new IngredientNameNormalizer());
        $this->factory = new RecipeIngredientFactory($ingredientResolver);
    }

    public function testCreateRecipeIngredient(): void
    {
        $ingredient = IngredientBuilder::create()
            ->withName('Farine')
            ->build();

        $recipeIngredients = $this->factory->createMany(
            [
                [
                    'name' => 'Farine',
                    'quantity' => 250,
                    'unit' => 'g',
                ]
            ]
        );

        self::assertSame($ingredient->getName(), $recipeIngredients[0]->getIngredient()->getName());
        self::assertEquals(250, $recipeIngredients[0]->getQuantity());
        self::assertSame('g', $recipeIngredients[0]->getUnit());
    }

    public function testCreateWithoutUnit(): void
    {
        $recipeIngredients = $this->factory->createMany(
            [
                [
                    'name' => 'farine',
                    'quantity' => 3,
                    'unit' => null,
                ]
            ]
        );

        self::assertNull($recipeIngredients[0]->getUnit());
    }

    public function testCreateWithUnitUnknown(): void
    {
        $recipeIngredients = $this->factory->createMany(
            [
                [
                    'name' => 'farine',
                    'quantity' => 3,
                    'unit' => 'oz',
                ]
            ]
        );

        self::assertEquals('oz', $recipeIngredients[0]->getUnit());
    }

    public function testCreateDecimalQuantity(): void
    {
        $recipeIngredients = $this->factory->createMany(
            [
                [
                    'name' => 'eau',
                    'quantity' => 12.5,
                    'unit' => 'cl',
                ]
            ]
        );

        self::assertEquals(12.5, $recipeIngredients[0]->getQuantity());
    }
}