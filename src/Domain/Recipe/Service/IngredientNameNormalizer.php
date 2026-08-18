<?php

namespace App\Domain\Recipe\Service;

use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\Language;

final class IngredientNameNormalizer
{
    public function normalize(string $name): string
    {
        $name = trim($name);

        $name = mb_strtolower($name);

        $name = preg_replace('/[()\-]/', ' ', $name);

        $name = preg_replace('/\s+/', ' ', $name);

        $name = preg_replace('/\d+/', '', $name);

        $inflector = InflectorFactory::createForLanguage(Language::FRENCH)->build();
        $name = $inflector->unaccent($name);

        $words = explode(' ', $name);

        $normalized = [];

        foreach ($words as $word) {
            if ('' === $word) {
                continue;
            }

            $normalized[] = $word;
        }

        return implode(' ', $normalized);
    }
}
