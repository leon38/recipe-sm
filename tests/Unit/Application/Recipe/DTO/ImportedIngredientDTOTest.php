<?php

namespace App\Tests\Unit\Application\Recipe\DTO;

use App\Application\Recipe\DTO\ImportedIngredientDTO;

final class ImportedIngredientDTOTest extends AbstractValidationTest
{
    public function testValidIngredient(): void
    {
        $dto = new ImportedIngredientDTO(
            name: 'Farine',
            quantity: 250,
            unit: 'g'
        );

        $this->assertHasNoViolation($dto);
    }

    public function testIngredientNameIsMandatory(): void
    {
        $dto = new ImportedIngredientDTO('');

        $this->assertViolationCount($dto, 1);
    }

    public function testQuantityCannotBeNegative(): void
    {
        $dto = new ImportedIngredientDTO('Farine', -5);

        $this->assertViolationCount($dto, 1);
    }
}
