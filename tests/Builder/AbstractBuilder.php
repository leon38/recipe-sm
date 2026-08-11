<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Recipe\ValueObject\ValueId;

abstract class AbstractBuilder
{
    protected function generateId(): ValueId
    {
        return ValueId::generate();
    }

    protected function generateStringId(): string
    {
        return (string) $this->generateId();
    }

    protected function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    protected function generateDate(string $date): \DateTimeImmutable
    {
        return new \DateTimeImmutable($date);
    }

    /**
     * @param array<mixed> $collection
     *
     * @return array<mixed>
     */
    protected function append(array $collection, mixed $value): array
    {
        $collection[] = $value;

        return $collection;
    }

    protected function valueOrDefault(mixed $value, mixed $default): mixed
    {
        return $value ?? $default;
    }
}
