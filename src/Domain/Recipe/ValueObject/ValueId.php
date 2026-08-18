<?php

namespace App\Domain\Recipe\ValueObject;

use Symfony\Component\Uid\Uuid;

final class ValueId
{
    public function __construct(
        private string $value,
    ) {
        if ($value === '') {
            throw new \InvalidArgumentException('ValueId cannot be empty.');
        }
    }

    public static function generate(): self
    {
        return new self(Uuid::v7()->toRfc4122());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(ValueId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
