<?php

namespace App\Infrastructure\Import\Html;

interface HtmlFetcherInterface
{
    public function fetch(string $url): string;
}