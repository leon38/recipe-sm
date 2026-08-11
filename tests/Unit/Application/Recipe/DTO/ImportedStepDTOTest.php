<?php

namespace App\Tests\Unit\Application\Recipe\DTO;

use App\Application\Recipe\DTO\ImportedStepDTO;

final class ImportedStepDTOTest extends AbstractValidationTestCase
{
    public function testValidStep(): void
    {
        $dto = new ImportedStepDTO('Une bonne instruction');

        $this->assertHasNoViolation($dto);
    }

    public function testInstructionIsMandatory(): void
    {
        $dto = new ImportedStepDTO('');

        $this->assertViolationCount($dto, 1);
    }
}
