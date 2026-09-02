<?php

namespace App\Tests\Unit\Domain\Recipe\Service;

use App\Domain\Recipe\Service\IngredientNameNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IngredientNormalizerTest extends TestCase
{
    private IngredientNameNormalizer $ingredientNormalizer;

    public function setUp(): void
    {
        $this->ingredientNormalizer = new IngredientNameNormalizer();
    }

    #[DataProvider('provideName')]
    public function testItNormalizesIngredientName(string $name, string $normalizedName): void
    {
        $resultNormalized = $this->ingredientNormalizer->normalize($name);

        $this->assertEquals($normalizedName, $resultNormalized);
    }

    /**
     * @return array<int, array{0: string, 1: string}>
     */
    public static function provideName(): array
    {
        return [
            ['Huile de sésame', 'huile de sesame'],
            ['Œufs', 'oeufs'],
        ];
    }
}
