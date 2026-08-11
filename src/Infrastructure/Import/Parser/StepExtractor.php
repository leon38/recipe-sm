<?php

namespace App\Infrastructure\Import\Parser;

use App\Application\Recipe\DTO\ImportedStepDTO;

final class StepExtractor extends ContentExtractor
{
    public const PREPARATION_HEADERS = [
        'préparation',
        'preparation',
        'étapes',
        'etapes',
        'instructions',
        'méthode',
        'method',
        'recette',
        'réalisation',
        'realisation',
        'marche à suivre',
        'déroulé',
    ];

    /**
     * @return ImportedStepDTO[]
     */
    public function extract(string $content): array
    {

        $lines = preg_split('/\R/', $content);
        $start = $this->findPreparationStart($lines);

        $steps = [];
        for ($i = $start; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if ($line !== '') {
                $steps[] = new ImportedStepDTO(
                    instruction: trim($lines[$i]),
                );
            }
        }

        return $steps;
    }

    /**
     * Finds the starting index of the preparation steps section.
     * @param array<string> $lines The lines of the content.
     * @return int The index of the first preparation step line.
     */
    private function findPreparationStart(array $lines): int
    {
        foreach ($lines as $index => $line) {

            $normalized = $this->normalize($line);

            foreach (self::PREPARATION_HEADERS as $header) {
                if (str_contains($normalized, $this->normalize($header))) {
                    return $index + 1;
                }
            }
        }

        return 0;
    }
}