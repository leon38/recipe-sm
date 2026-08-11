<?php

namespace App\Infrastructure\Doctrine;

use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class ValueIdType extends StringType
{
    public const NAME = 'value_id';

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ValueId
    {
        return null !== $value ? ValueId::fromString($value) : null;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return null !== $value ? (string) $value : null;
    }
}
