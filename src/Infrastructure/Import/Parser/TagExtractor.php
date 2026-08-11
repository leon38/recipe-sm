<?php

namespace App\Infrastructure\Import\Parser;

use App\Application\Recipe\DTO\ImportedTagDTO;

final class TagExtractor
{
    /**
     * @return ImportedTagDTO[]
     */
    public function extract(string $content): array
    {
        preg_match_all(
            '/(?<!\w)#([\p{L}\p{N}_]+)/u',
            $content,
            $matches
        );

        $tags = array_values(
            array_unique(
                array_map(
                    static fn (string $tag): string => mb_strtolower(trim($tag)),
                    $matches[1]
                )
            )
        );

        return array_map(static fn (string $tag): ImportedTagDTO => new ImportedTagDTO($tag), $tags);
    }
}
