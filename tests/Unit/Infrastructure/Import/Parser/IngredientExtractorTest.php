<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Import\Parser;

use App\Domain\Recipe\Service\IngredientNameNormalizer;
use App\Infrastructure\Import\Parser\IngredientExtractor;
use App\Infrastructure\Import\Parser\QuantityNormalizer;
use App\Infrastructure\Import\Parser\UnitNormalizer;
use PHPUnit\Framework\TestCase;

final class IngredientExtractorTest extends TestCase
{
    private UnitNormalizer $unitNormalizer;
    private QuantityNormalizer $quantityNormalizer;
    private IngredientNameNormalizer $ingredientNameNormalizer;

    private IngredientExtractor $extractor;

    protected function setUp(): void
    {
        $this->unitNormalizer = new UnitNormalizer();
        $this->quantityNormalizer = new QuantityNormalizer();
        $this->ingredientNameNormalizer = new IngredientNameNormalizer();

        $this->extractor = new IngredientExtractor(
            $this->unitNormalizer,
            $this->quantityNormalizer,
            $this->ingredientNameNormalizer,
        );
    }

    public function testItExtractsIngredientsAfterIngredientsHeader(): void
    {
        $content = <<<'TEXT'
Ma recette

Ingrédients
2 tomates
1 oignon
100 g farine

Préparation
Mélanger tous les ingrédients.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(3, $ingredients);

        self::assertSame('tomates', $ingredients[0]->name);
        self::assertSame(2.0, $ingredients[0]->quantity);

        self::assertSame('oignon', $ingredients[1]->name);
        self::assertSame(1.0, $ingredients[1]->quantity);

        self::assertSame('farine', $ingredients[2]->name);
        self::assertSame(100.0, $ingredients[2]->quantity);
        self::assertSame('g', $ingredients[2]->unit);
    }

    public function testItIgnoresContentBeforeIngredientsHeader(): void
    {
        $content = <<<'TEXT'
Cette recette est délicieuse.
2 tomates
1 oignon

Ingrédients
3 carottes

Préparation
Faire cuire.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(1, $ingredients);
        self::assertSame('carottes', $ingredients[0]->name);
        self::assertSame(3.0, $ingredients[0]->quantity);
    }

    public function testItStopsAtPreparationHeader(): void
    {
        $content = <<<'TEXT'
Ingrédients
2 tomates
1 oignon

Préparation
Mélanger les tomates et les oignons.
100 g de farine
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(2, $ingredients);

        self::assertSame('tomates', $ingredients[0]->name);
        self::assertSame('oignon', $ingredients[1]->name);
    }

    public function testItExtractsIngredientWithoutUnit(): void
    {
        $content = <<<'TEXT'
Ingrédients
2 tomates
1 oignon

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(2, $ingredients);

        self::assertSame(2.0, $ingredients[0]->quantity);
        self::assertNull($ingredients[0]->unit);
        self::assertSame('tomates', $ingredients[0]->name);

        self::assertSame(1.0, $ingredients[1]->quantity);
        self::assertNull($ingredients[1]->unit);
        self::assertSame('oignon', $ingredients[1]->name);
    }

    public function testItExtractsIngredientWithUnit(): void
    {
        $content = <<<'TEXT'
Ingrédients
200 g farine
500 ml lait
2 kg pommes de terre

Préparation
Cuire.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(3, $ingredients);

        self::assertSame(200.0, $ingredients[0]->quantity);
        self::assertSame('g', $ingredients[0]->unit);
        self::assertSame('farine', $ingredients[0]->name);

        self::assertSame(500.0, $ingredients[1]->quantity);
        self::assertSame('ml', $ingredients[1]->unit);
        self::assertSame('lait', $ingredients[1]->name);

        self::assertSame(2.0, $ingredients[2]->quantity);
        self::assertSame('kg', $ingredients[2]->unit);
        self::assertSame('pommes de terre', $ingredients[2]->name);
    }

    public function testItExtractsDecimalQuantityWithComma(): void
    {
        $content = <<<'TEXT'
Ingrédients
1,5 kg farine
0,5 l lait

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(2, $ingredients);

        self::assertSame(1.5, $ingredients[0]->quantity);
        self::assertSame('kg', $ingredients[0]->unit);

        self::assertSame(0.5, $ingredients[1]->quantity);
        self::assertSame('l', $ingredients[1]->unit);
    }

    public function testItExtractsDecimalQuantityWithDot(): void
    {
        $content = <<<'TEXT'
Ingrédients
1.5 kg farine
0.5 l lait

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(2, $ingredients);

        self::assertSame(1.5, $ingredients[0]->quantity);
        self::assertSame(0.5, $ingredients[1]->quantity);
    }

    public function testItRemovesBulletCharacters(): void
    {
        $content = <<<'TEXT'
Ingrédients
- 2 tomates
* 1 oignon
• 100 g farine

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(3, $ingredients);

        self::assertSame('tomates', $ingredients[0]->name);
        self::assertSame('oignon', $ingredients[1]->name);
        self::assertSame('farine', $ingredients[2]->name);
    }

    public function testItIgnoresEmptyLines(): void
    {
        $content = <<<'TEXT'
Ingrédients

2 tomates


1 oignon


Préparation

Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(2, $ingredients);
    }

    public function testItSupportsSingularIngredientHeader(): void
    {
        $content = <<<'TEXT'
Ingrédient
2 tomates

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(1, $ingredients);
        self::assertSame('tomates', $ingredients[0]->name);
    }

    public function testItSupportsIngredientWithoutAccent(): void
    {
        $content = <<<'TEXT'
Ingredient
2 tomates

Preparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(1, $ingredients);
        self::assertSame('tomates', $ingredients[0]->name);
    }

    public function testItSupportsPourLaPateHeader(): void
    {
        $content = <<<'TEXT'
Pour la pâte
250 g farine
100 ml lait

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(2, $ingredients);

        self::assertSame('farine', $ingredients[0]->name);
        self::assertSame('lait', $ingredients[1]->name);
    }

    public function testItSupportsPourLaGarnitureHeader(): void
    {
        $content = <<<'TEXT'
Pour la garniture
200 g chocolat
100 ml crème

Préparation
Faire fondre.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(2, $ingredients);

        self::assertSame('chocolat', $ingredients[0]->name);
        self::assertSame('creme', $ingredients[1]->name);
    }

    public function testItSupportsVousAurezBesoinDeHeader(): void
    {
        $content = <<<'TEXT'
Vous aurez besoin de
2 œufs
100 g sucre

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(2, $ingredients);

        self::assertSame('oeufs', $ingredients[0]->name);
        self::assertSame('sucre', $ingredients[1]->name);
    }

    public function testItReturnsEmptyArrayWhenNoIngredientHeaderExists(): void
    {
        $content = <<<'TEXT'
Cette recette est délicieuse.

2 tomates
1 oignon

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertSame([], $ingredients);
    }

    public function testItIgnoresInvalidIngredientLines(): void
    {
        $content = <<<'TEXT'
Ingrédients
Tomates
Quelques oignons
2 tomates

Préparation
Mélanger.
TEXT;

        $ingredients = $this->extractor->extract($content);

        self::assertCount(1, $ingredients);
        self::assertSame('tomates', $ingredients[0]->name);
    }

    public function testItNormalizesUnit(): void
    {
        $this->unitNormalizer = $this->createMock(UnitNormalizer::class);

        $this->unitNormalizer
            ->expects(self::once())
            ->method('normalize')
            ->with('cuillères à soupe')
            ->willReturn('càs');

        $this->extractor = new IngredientExtractor(
            $this->unitNormalizer,
            $this->quantityNormalizer,
            $this->ingredientNameNormalizer,
        );

        $ingredients = $this->extractor->extract(
            <<<'TEXT'
Ingrédients
2 cuillères à soupe huile

Préparation
Mélanger.
TEXT
        );

        self::assertCount(1, $ingredients);
        self::assertSame('càs', $ingredients[0]->unit);
    }

    public function testItNormalizesQuantity(): void
    {
        $this->quantityNormalizer = $this->createMock(QuantityNormalizer::class);

        $this->quantityNormalizer
            ->expects(self::once())
            ->method('normalize')
            ->with('1,5')
            ->willReturn(1.5);

        $this->extractor = new IngredientExtractor(
            $this->unitNormalizer,
            $this->quantityNormalizer,
            $this->ingredientNameNormalizer,
        );

        $ingredients = $this->extractor->extract(
            <<<'TEXT'
Ingrédients
1,5 kg farine

Préparation
Mélanger.
TEXT
        );

        self::assertCount(1, $ingredients);
        self::assertSame(1.5, $ingredients[0]->quantity);
    }

    public function testItNormalizesIngredientName(): void
    {
        $this->extractor = new IngredientExtractor(
            $this->unitNormalizer,
            $this->quantityNormalizer,
            $this->ingredientNameNormalizer,
        );

        $ingredients = $this->extractor->extract(
            <<<'TEXT'
Ingrédients
2 Tomates

Préparation
Mélanger.
TEXT
        );

        self::assertCount(1, $ingredients);
        self::assertSame('tomates', $ingredients[0]->name);
    }
}
