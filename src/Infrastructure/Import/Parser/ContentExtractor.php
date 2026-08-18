<?php

namespace App\Infrastructure\Import\Parser;

class ContentExtractor
{
    /**
     * Checks if a line contains any of the specified headers.
     *
     * @param string        $line    the line to check
     * @param array<string> $headers the headers to look for
     *
     * @return bool true if the line contains any of the headers, false otherwise
     */
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

        // Supprime les emojis et la ponctuation
        $line = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $line);

        $line = preg_replace('/\s+/', ' ', $line);

        return trim($line);
    }
}
