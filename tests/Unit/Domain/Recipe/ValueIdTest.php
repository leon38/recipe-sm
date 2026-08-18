<?php

namespace App\Tests\Unit\Domain\Recipe;

use App\Domain\Recipe\ValueObject\ValueId;
use PHPUnit\Framework\TestCase;

class ValueIdTest extends TestCase
{
    public function testItCreatesAValueIdFromString(): void
    {
        $valueId = new ValueId('12345');
        $this->assertEquals('12345', $valueId->getValue());
    }

    public function testItGeneratesAValueId(): void
    {
        $valueId = ValueId::generate();
        $this->assertInstanceOf(ValueId::class, $valueId);
        $this->assertNotEmpty($valueId->getValue());
    }

    public function testItReturnsItsStringValue(): void
    {
        $valueId = new ValueId('12345');
        $this->assertEquals('12345', $valueId->getValue());
    }

    public function testItRejectsInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ValueId('');
    }
}
