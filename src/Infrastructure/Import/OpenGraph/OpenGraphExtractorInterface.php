<?php

namespace App\Infrastructure\Import\OpenGraph;

interface OpenGraphExtractorInterface
{
    public function extract(string $html): OpenGraphData;
}
