<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Recipe\Factory;

use App\Application\Recipe\Factory\RecipeIngredientFactory;
use App\Application\Recipe\Resolver\IngredientResolver;
use App\Domain\Recipe\Repository\IngredientRepositoryInterface;
use App\Domain\Recipe\Service\IngredientNameNormalizer;
use App\Tests\Builder\IngredientBuilder;
use PHPUnit\Framework\TestCase;

final class RecipeIngredientFactoryTest extends TestCase
{
    private RecipeIngredientFactory $factory;
    private IngredientRepositoryInterface $ingredientRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ingredientRepository = $this->createStub(IngredientRepositoryInterface::class);
        $ingredientResolver = new IngredientResolver($this->ingredientRepository, new IngredientNameNormalizer());
        $this->factory = new RecipeIngredientFactory($ingredientResolver);
    }

    public function testCreateRecipeIngredient(): void
    {
        $ingredient = IngredientBuilder::create()
            ->withName('farine')
            ->build();

        $recipeIngredients = $this->factory->createMany(
            [
                [
                    'name' => 'farine',
                    'quantity' => 250,
                    'unit' => 'g',
                ],
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
                ],
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
                ],
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
                ],
            ]
        );

        self::assertEquals(12.5, $recipeIngredients[0]->getQuantity());
    }
}
