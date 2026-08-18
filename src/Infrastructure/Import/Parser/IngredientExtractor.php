<?php

namespace App\Infrastructure\Import\Parser;

use App\Application\Recipe\DTO\ImportedIngredientDTO;
use App\Domain\Recipe\Service\IngredientNameNormalizer;

final class IngredientExtractor extends ContentExtractor
{
    private const UNITS = [
        'g',
        'kg',
        'mg',
        'ml',
        'cl',
        'l',
        'càs',
        'cas',
        'cac',
        'c. à c',
        'c. à c.',
        'c. à s.',
        'c. soupe',
        'cuillère à soupe',
        'cuillère à café',
        'cuillères à soupe',
        'cuillères à café',
        'cuillère(s) à soupe',
        'cuillère(s) à café',
    ];

    private const INGREDIENT_HEADERS = [
        'Ingrédient',
        'Ingredient',
        'Ingrédients',
        'Ingredients',
        'ingrédients',
        'ingredients',
        'ingrédient',
        'ingredient',
        'pour la pâte',
        'pour la garniture',
        'vous aurez besoin de',
        'liste des ingrédients',
    ];

    public function __construct(
        private UnitNormalizer $unitNormalizer,
        private QuantityNormalizer $quantityNormalizer,
        private IngredientNameNormalizer $ingredientNameNormalizer,
    ) {
    }

    /**
     * @return ImportedIngredientDTO[]
     */
    public function extract(string $content): array
    {
        $lines = preg_split('/\R/', $content);
        if (false === $lines) {
            return [];
        }

        $start = $this->findIngredientsStart($lines);
        if (0 === $start) {
            return [];
        }

        $ingredients = [];

        for ($i = $start; $i < count($lines); ++$i) {
            $line = str_replace(['-', '*', '•'], '', $lines[$i]);
            $line = trim($line);

            if ('' === $line) {
                continue;
            }

            // On s'arrête lorsqu'on rencontre la préparation
            if ($this->looksLikePreparationHeader($line)) {
                break;
            }

            $ingredient = $this->extractStructuredIngredient($line);

            if (null !== $ingredient) {
                $ingredients[] = $ingredient;
            }
        }

        return $ingredients;
    }

    private function extractStructuredIngredient(
        string $line,
    ): ?ImportedIngredientDTO {
        $unitsPattern = implode('|', array_map('preg_quote', self::UNITS));

        $pattern = sprintf(
            '/^(\d+(?:[,.]\d+)?)\s*(%s)?\s+(.+)$/iu',
            $unitsPattern
        );

        preg_match_all($pattern, $line, $matches);

        if (empty($matches[1]) || empty($matches[3])) {
            return null;
        }

        $unit = isset($matches[2][0]) ? trim($matches[2][0]) : null;

        $unit = $this->unitNormalizer->normalize($unit);

        $quantity = $this->quantityNormalizer->normalize($matches[1][0]);

        $name = $this->ingredientNameNormalizer->normalize($matches[3][0]);

        return new ImportedIngredientDTO(
            name: $name,
            quantity: $quantity,
            unit: $unit,
        );
    }

    /**
     * Finds the starting index of the ingredients section.
     *
     * @param list<string>|false $lines the lines of the content
     *
     * @return int the index of the first ingredient line
     */
    private function findIngredientsStart(array|bool $lines): int
    {
        if (!is_array($lines)) {
            return 0;
        }

        foreach ($lines as $index => $line) {
            $normalized = $this->normalize($line);

            foreach (self::INGREDIENT_HEADERS as $header) {
                if (str_contains($normalized, $this->normalize($header))) {
                    return $index + 1;
                }
            }
        }

        return 0;
    }

    private function looksLikePreparationHeader(string $line): bool
    {
        return $this->containsHeader($line, StepExtractor::PREPARATION_HEADERS);
    }
}
