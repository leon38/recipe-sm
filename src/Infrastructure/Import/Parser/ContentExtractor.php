<?php
namespace App\Infrastructure\Import\Parser;

class ContentExtractor
{

    public function containsHeader(string $line, array $headers): bool
    {
        $normalized = $this->normalize($line);

        foreach ($headers as $header) {
            if (str_contains($normalized, $this->normalize($header))) {
                return true;
            }
        }

        return false;
    }

    protected function normalize(string $line): string
    {
        $line = mb_strtolower(trim($line));

        $line = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $line);

        // Supprime les emojis et la ponctuation
        $line = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $line);

        $line = preg_replace('/\s+/', ' ', $line);

        return trim($line);
    }

}
