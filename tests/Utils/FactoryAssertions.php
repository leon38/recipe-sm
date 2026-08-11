<?php

namespace App\Tests\Utils;

use PHPUnit\Framework\Assert;

trait FactoryAssertions
{
    protected function assertGeneratedId(object $entity): void
    {
        Assert::assertTrue(!is_null($entity->getId()));
    }

    protected function assertInitializedTimestamps(object $entity): void
    {
        Assert::assertNotEmpty($entity->getCreatedAt());
        Assert::assertNotEmpty($entity->getUpdatedAt());
    }
}
