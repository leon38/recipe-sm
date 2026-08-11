<?php
namespace App\Infrastructure\Import\Parser;

final class IngredientNameNormalizer
{
    private const STOP_WORDS = [
        'de',
        'du',
        'des',
        'd',
        'la',
        'le',
        'les',
        'un',
        'une',
        'à',
        'au',
        'aux',
    ];

    public function normalize(string $name): string
    {
        $name = trim($name);

        $name = mb_strtolower($name);

        $name = preg_replace('/[()]/', ' ', $name);

        $name = preg_replace('/\s+/', ' ', $name);

        $words = explode(' ', $name);

        $normalized = [];


        foreach ($words as $word) {

            if ($word === '') {
                continue;
            }

            if (in_array($word, self::STOP_WORDS, true)) {
                continue;
            }

            $normalized[] = $word;
        }

        return implode(' ', $normalized);
    }
}