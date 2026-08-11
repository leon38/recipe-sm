<?php

namespace App\Infrastructure\Import\Parser;

use App\Domain\Recipe\Enum\Unit;

final class UnitNormalizer
{
    private const UNIT_ALIASES = [
        'cas' => Unit::TABLESPOON,
        'cas.' => Unit::TABLESPOON,
        'c a s' => Unit::TABLESPOON,
        'c à s' => Unit::TABLESPOON,
        'c. soupe' => Unit::TABLESPOON,
        'c. à soupe' => Unit::TABLESPOON,
        'cuillere a soupe' => Unit::TABLESPOON,
        'cuilleres a soupe' => Unit::TABLESPOON,
        'cuillère à soupe' => Unit::TABLESPOON,
        'cuillères à soupe' => Unit::TABLESPOON,

        'cac' => Unit::TEASPOON,
        'c a c' => Unit::TEASPOON,
        'c à c' => Unit::TEASPOON,
        'c. café' => Unit::TEASPOON,
        'c. à café' => Unit::TEASPOON,
        'cuillere a cafe' => Unit::TEASPOON,
        'cuilleres a cafe' => Unit::TEASPOON,
        'cuillère à café' => Unit::TEASPOON,
        'cuillères à café' => Unit::TEASPOON,

        'kg' => Unit::KILOGRAM,
        'g' => Unit::GRAM,
        'l' => Unit::LITER,
        'cl' => Unit::CENTILITER,
        'ml' => Unit::MILLILITER,
    ];

    public function normalize(?string $unit): ?string
    {
        if (null === $unit) {
            return null;
        }

        $unit = trim($unit);

        if ('' === $unit) {
            return null;
        }

        $unit = mb_strtolower($unit);

        // suppression des accents
        $unit = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $unit);

        // suppression des points
        $unit = str_replace('.', '', $unit);

        // espaces multiples
        $unit = preg_replace('/\s+/', ' ', $unit);

        return self::UNIT_ALIASES[$unit]->value ?? $unit;
    }
}
