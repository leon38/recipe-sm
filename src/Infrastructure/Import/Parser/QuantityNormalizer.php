<?php

namespace App\Infrastructure\Import\Parser;

class QuantityNormalizer
{
    private const QUANTITY_ALIASES = [
        '1,5' => 1.5,
        '½' => 0.5,
        '1/2' => 0.5,
        '¼' => 0.25,
        '¾' => 0.75,
        'une' => 1,
        'un' => 1,
    ];

    public function normalize(?string $quantity): ?float
    {
        if (null === $quantity) {
            return null;
        }

        $quantity = trim($quantity);

        if ('' === $quantity) {
            return null;
        }

        $quantity = mb_strtolower($quantity);

        // suppression des accents
        $quantity = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $quantity);

        // remplacement des virgules par des points
        $quantity = str_replace(',', '.', $quantity);

        // espaces multiples
        $quantity = preg_replace('/\s+/', ' ', $quantity);

        return self::QUANTITY_ALIASES[$quantity] ?? $quantity;
    }
}
