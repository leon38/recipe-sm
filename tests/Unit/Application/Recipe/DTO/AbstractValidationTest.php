<?php

namespace App\Tests\Unit\Application\Recipe\DTO;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidationTest extends KernelTestCase
{
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    protected function assertHasNoViolation(object $dto): void
    {
        self::assertCount(
            0,
            $this->validator->validate($dto)
        );
    }

    protected function assertViolationCount(
        object $dto,
        int $count
    ): void {
        self::assertCount(
            $count,
            $this->validator->validate($dto)
        );
    }
}