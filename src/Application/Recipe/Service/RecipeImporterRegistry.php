<?php
namespace App\Application\Recipe\Service;

final class RecipeImporterRegistry
{
    /**
     * @param iterable<RecipeImporterInterface> $importers
     */
    public function __construct(
        private readonly iterable $importers,
    ) {
    }

    public function getImporter(string $url): RecipeImporterInterface
    {
        foreach ($this->importers as $importer) {
            if ($importer->supports($url)) {
                return $importer;
            }
        }

        throw new \RuntimeException(
            sprintf('No importer found for "%s"', $url)
        );
    }
}