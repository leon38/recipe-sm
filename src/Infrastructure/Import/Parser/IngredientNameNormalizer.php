<?php
namespace App\Infrastructure\Import\Parser;

use Wamania\Snowball\Stemmer\French;

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

        //$name = $this->removeAccents($name);

        $name = preg_replace('/[()]/', ' ', $name);

        $name = preg_replace('/\s+/', ' ', $name);

        $words = explode(' ', $name);

        $normalized = [];

        $stemmer = new French();

        foreach ($words as $word) {

            if ($word === '') {
                continue;
            }

            if (in_array($word, self::STOP_WORDS, true)) {
                continue;
            }

            $normalized[] = $word; //$stemmer->stem($word);
        }

        return implode(' ', $normalized);
    }

    private function removeAccents(string $text): string
    {
        return iconv(
            'UTF-8',
            'ASCII//TRANSLIT//IGNORE',
            $text,
        );
    }
}