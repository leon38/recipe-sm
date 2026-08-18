<?php

namespace App\Tests\Unit\Application\Recipe\Resolver;

use App\Application\Recipe\Resolver\IngredientResolver;
use App\Domain\Recipe\Repository\IngredientRepositoryInterface;
use App\Domain\Recipe\Service\IngredientNameNormalizer;
use App\Tests\Builder\IngredientBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class IngredientResolverTest extends TestCase
{
    private IngredientResolver $resolver;
    private IngredientRepositoryInterface|MockObject $ingredientRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ingredientRepository = $this->createMock(IngredientRepositoryInterface::class);
        $this->resolver = new IngredientResolver($this->ingredientRepository, new IngredientNameNormalizer());
    }

    public function testResolveNewIngredient(): void
    {
        $this->ingredientRepository
            ->expects($this->once())
            ->method('findOneByNormalizedName')
            ->with('sucre')
            ->willReturn(null);

        $resolvedIngredient = $this->resolver->resolve('Sucre');

        self::assertSame('Sucre', $resolvedIngredient->getName());
        self::assertSame('sucre', $resolvedIngredient->getNormalizedName());
    }

    #[DataProvider('specialIngredientsProvider')]
    public function testResolveWithSpecialIngredients(string $name, string $normalizedName, string $resolvedName, string $resolvedNormalizedName): void
    {
        $ingredient = IngredientBuilder::create()
            ->withName($name)
            ->withNormalizedName($normalizedName)
            ->build();

        $this->ingredientRepository
            ->expects($this->once())
            ->method('findOneByNormalizedName')
            ->with($normalizedName)
            ->willReturn($ingredient);

        $resolvedIngredient = $this->resolver->resolve($name);

        self::assertSame($resolvedIngredient->getName(), $resolvedName);
        self::assertSame($resolvedIngredient->getNormalizedName(), $resolvedNormalizedName);
    }

    public static function specialIngredientsProvider(): array
    {
        return [
            ['Farine', 'farine', 'Farine', 'farine'],
            ['Crème fraîche', 'creme fraiche', 'Crème fraîche', 'creme fraiche'],
            ['Œuf', 'oeuf', 'Œuf', 'oeuf'],
            ['2 tomates', 'tomates', '2 tomates', 'tomates'],
            ['  lait  ', 'lait', '  lait  ', 'lait'],
            ['beurre', 'beurre', 'beurre', 'beurre'],
            ['ananas', 'ananas', 'ananas', 'ananas'],
            ['Riz', 'riz', 'Riz', 'riz'],
            ['pommes de terre', 'pommes de terre', 'pommes de terre', 'pommes de terre'],
        ];
    }
}
