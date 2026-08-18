<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Import\Parser;

use App\Infrastructure\Import\Parser\StepExtractor;
use PHPUnit\Framework\TestCase;

final class StepExtractorTest extends TestCase
{
    private StepExtractor $extractor;

    protected function setUp(): void
    {
        $this->extractor = new StepExtractor();
    }

    public function testItExtractsStepsAfterPreparationHeader(): void
    {
        $content = <<<'TEXT'
Ma recette

Ingrédients
2 tomates
1 oignon

Préparation
Couper les tomates.
Émincer l'oignon.
Mélanger les ingrédients.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame(
            'Couper les tomates.',
            $steps[0]->instruction,
        );

        self::assertSame(
            'Émincer l\'oignon.',
            $steps[1]->instruction,
        );

        self::assertSame(
            'Mélanger les ingrédients.',
            $steps[2]->instruction,
        );
    }

    public function testItIgnoresContentBeforePreparationHeader(): void
    {
        $content = <<<'TEXT'
Cette recette est délicieuse.

Ingrédients
2 tomates
1 oignon

Préparation
Couper les tomates.
Mélanger les ingrédients.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(2, $steps);

        self::assertSame(
            'Couper les tomates.',
            $steps[0]->instruction,
        );

        self::assertSame(
            'Mélanger les ingrédients.',
            $steps[1]->instruction,
        );
    }

    public function testItIgnoresEmptyLines(): void
    {
        $content = <<<'TEXT'
Préparation

Couper les tomates.


Émincer l'oignon.


Mélanger les ingrédients.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame('Couper les tomates.', $steps[0]->instruction);
        self::assertSame('Émincer l\'oignon.', $steps[1]->instruction);
        self::assertSame('Mélanger les ingrédients.', $steps[2]->instruction);
    }

    public function testItTrimsInstructions(): void
    {
        $content = <<<'TEXT'
Préparation
   Couper les tomates.   
     Émincer l'oignon.
  Mélanger les ingrédients.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame(
            'Couper les tomates.',
            $steps[0]->instruction,
        );

        self::assertSame(
            'Émincer l\'oignon.',
            $steps[1]->instruction,
        );

        self::assertSame(
            'Mélanger les ingrédients.',
            $steps[2]->instruction,
        );
    }

    public function testItSupportsPreparationHeaderWithoutAccent(): void
    {
        $content = <<<'TEXT'
Preparation
Couper les tomates.
Mélanger les ingrédients.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(2, $steps);

        self::assertSame(
            'Couper les tomates.',
            $steps[0]->instruction,
        );

        self::assertSame(
            'Mélanger les ingrédients.',
            $steps[1]->instruction,
        );
    }

    public function testItSupportsEtapesHeader(): void
    {
        $content = <<<'TEXT'
Étapes
Couper les tomates.
Émincer l'oignon.
Mélanger.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame(
            'Couper les tomates.',
            $steps[0]->instruction,
        );

        self::assertSame(
            'Émincer l\'oignon.',
            $steps[1]->instruction,
        );

        self::assertSame(
            'Mélanger.',
            $steps[2]->instruction,
        );
    }

    public function testItSupportsEtapesHeaderWithoutAccent(): void
    {
        $content = <<<'TEXT'
Etapes
Couper les tomates.
Émincer l'oignon.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(2, $steps);

        self::assertSame(
            'Couper les tomates.',
            $steps[0]->instruction,
        );

        self::assertSame(
            'Émincer l\'oignon.',
            $steps[1]->instruction,
        );
    }

    public function testItSupportsInstructionsHeader(): void
    {
        $content = <<<'TEXT'
Instructions
Couper les tomates.
Ajouter le sel.
Mélanger.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame('Couper les tomates.', $steps[0]->instruction);
        self::assertSame('Ajouter le sel.', $steps[1]->instruction);
        self::assertSame('Mélanger.', $steps[2]->instruction);
    }

    public function testItSupportsMethodeHeader(): void
    {
        $content = <<<'TEXT'
Méthode
Couper les tomates.
Faire revenir les oignons.
Mélanger.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame('Couper les tomates.', $steps[0]->instruction);
        self::assertSame('Faire revenir les oignons.', $steps[1]->instruction);
        self::assertSame('Mélanger.', $steps[2]->instruction);
    }

    public function testItSupportsMethodHeaderWithoutAccent(): void
    {
        $content = <<<'TEXT'
Method
Cut the tomatoes.
Add the onions.
Mix everything.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame('Cut the tomatoes.', $steps[0]->instruction);
        self::assertSame('Add the onions.', $steps[1]->instruction);
        self::assertSame('Mix everything.', $steps[2]->instruction);
    }

    public function testItSupportsRealisationHeader(): void
    {
        $content = <<<'TEXT'
Réalisation
Couper les tomates.
Ajouter les oignons.
Faire cuire.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame('Couper les tomates.', $steps[0]->instruction);
        self::assertSame('Ajouter les oignons.', $steps[1]->instruction);
        self::assertSame('Faire cuire.', $steps[2]->instruction);
    }

    public function testItSupportsRealisationHeaderWithoutAccent(): void
    {
        $content = <<<'TEXT'
Realisation
Couper les tomates.
Ajouter les oignons.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(2, $steps);

        self::assertSame('Couper les tomates.', $steps[0]->instruction);
        self::assertSame('Ajouter les oignons.', $steps[1]->instruction);
    }

    public function testItSupportsMarcheASuivreHeader(): void
    {
        $content = <<<'TEXT'
Marche à suivre
Couper les tomates.
Ajouter le sel.
Mélanger.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame('Couper les tomates.', $steps[0]->instruction);
        self::assertSame('Ajouter le sel.', $steps[1]->instruction);
        self::assertSame('Mélanger.', $steps[2]->instruction);
    }

    public function testItSupportsDerouleHeader(): void
    {
        $content = <<<'TEXT'
Déroulé
Couper les tomates.
Faire revenir les oignons.
Servir chaud.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(3, $steps);

        self::assertSame(
            'Couper les tomates.',
            $steps[0]->instruction,
        );

        self::assertSame(
            'Faire revenir les oignons.',
            $steps[1]->instruction,
        );

        self::assertSame(
            'Servir chaud.',
            $steps[2]->instruction,
        );
    }

    public function testItReturnsEmptyArrayWhenNoPreparationHeaderExists(): void
    {
        $content = <<<'TEXT'
Cette recette est délicieuse.

Couper les tomates.
Ajouter le sel.
Mélanger.
TEXT;

        self::assertSame(
            [],
            $this->extractor->extract($content),
        );
    }

    public function testItPreservesInstructionContent(): void
    {
        $content = <<<'TEXT'
Préparation
Faire cuire pendant 10 minutes à feu moyen, puis laisser
refroidir pendant 5 minutes.
TEXT;

        $steps = $this->extractor->extract($content);

        self::assertCount(2, $steps);

        self::assertSame(
            'Faire cuire pendant 10 minutes à feu moyen, puis laisser',
            $steps[0]->instruction,
        );

        self::assertSame(
            'refroidir pendant 5 minutes.',
            $steps[1]->instruction,
        );
    }

    public function testItSupportsWindowsLineEndings(): void
    {
        $content = "Préparation\r\nCouper les tomates.\r\nMélanger.\r\n";

        $steps = $this->extractor->extract($content);

        self::assertCount(2, $steps);

        self::assertSame(
            'Couper les tomates.',
            $steps[0]->instruction,
        );

        self::assertSame(
            'Mélanger.',
            $steps[1]->instruction,
        );
    }

    public function testItSupportsUnixLineEndings(): void
    {
        $content = "Préparation\nCouper les tomates.\nMélanger.\n";

        $steps = $this->extractor->extract($content);

        self::assertCount(2, $steps);
    }
}